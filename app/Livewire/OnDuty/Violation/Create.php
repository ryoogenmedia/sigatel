<?php

namespace App\Livewire\OnDuty\Violation;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Violation;
use App\Models\ViolationType;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Create extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'kelas'  => '',
    ];

    public $siswa;
    public $guru;
    public $jenisPelanggaran;
    public $deskripsi;

    public function clearForm(){
        $this->reset([
            'siswa',
            'guru',
            'jenisPelanggaran',
            'deskripsi',
        ]);
    }

    public function chooseStudent($id){
        $student = Student::find($id);
        $this->siswa = $student->id;
    }

    public function cancelStudent(){
        $this->clearForm();
    }

    #[Computed()]
    public function grades(){
        return Grade::all(['name', 'id']);
    }

    #[Computed()]
    public function teachers(){
        return Teacher::query()
            ->where('status', 'aktif')
            ->where('duty_status',true)
            ->get(['name', 'id']);
    }

    #[Computed()]
    public function violationTypes(){
        return ViolationType::query()
            ->where('status', true)
            ->get(['name', 'id']);
    }

    #[Computed()]
    public function students()
    {
        $query = Student::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('nis', 'LIKE', "%$search%");
            })
            ->when($this->filters['kelas'], function ($query, $kelas) {
                $query->where('grade_id', $kelas);
            })
            ->where('status','aktif')->latest();

        if($this->siswa){
            $query->where('id', $this->siswa);
        }

        return $this->applyPagination($query);
    }

    public function save(){
        if(!$this->siswa){
            session()->flash('alert', [
                'type' => 'warning',
                'message' => 'Bahaya.',
                'detail' => "Anda belum memilih siswa, pilih siswa terlebih dahulu.",
            ]);

            return redirect()->back();
        }

        if(!$this->guru){
            session()->flash('alert', [
                'type' => 'warning',
                'message' => 'Bahaya.',
                'detail' => "Anda belum memilih guru, pilih guru terlebih dahulu.",
            ]);

            return redirect()->back();
        }

        $this->validate([
            'siswa'             => ['required'],
            'guru'              => ['required'],
            'jenisPelanggaran'  => ['required','string','min:1','max:255'],
            'deskripsi'         => ['nullable','string','min:3','max:255'],
        ]);

        try{
            DB::beginTransaction();

            Violation::create([
                'student_id'     => $this->siswa,
                'teacher_id'     => $this->guru,
                'violation_type' => $this->jenisPelanggaran,
                'description'    => $this->deskripsi,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[pelanggaran] ' .
                auth()->user()->username .
                ' gagal menambahkan pelanggaran siswa. ' .
                $e->getMessage()
            );

            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal.',
                'detail' => "data pelanggaran siswa gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data pelanggaran siswa berhasil ditambah.",
        ]);

        return redirect()->route('on-duty.student-violation.index');
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function render()
    {
        return view('livewire.on-duty.violation.create');
    }
}
