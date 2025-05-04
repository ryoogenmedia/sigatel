<?php

namespace App\Livewire\Mobile\Duty;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Student;
use App\Models\Violation;
use App\Models\ViolationType;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    #[Title('Piket')]
    #[Layout('layouts.mobile-base')]

    public $teacherId;
    public $studentId;
    public $studentName;
    public $studentNIS;

    public $keteranganPelanggaran;
    public $jenisPelanggaran;

    public $filters = [
        'search' => '',
    ];

    public function chooseStudent($id){
        $student = Student::findOrFail($id);
        $this->studentId = $student->id;
        $this->studentName = $student->name;
        $this->studentNIS = $student->nis;
    }

    public function cancelStudent(){
        $this->reset(['studentId']);
    }

    #[Computed()]
    public function violation_types(){
        return ViolationType::query()
            ->where('status', true)
            ->get(['id','name']);
    }

    #[Computed()]
    public function students(){
        $query = Student::query()
            ->when($this->filters['search'], function($query,$search){
                $query->whereAny(['name','nis'], 'LIKE', "%$search%")
                ->orWhereHas('grade', function($query) use ($search){
                    $query->where('name', 'LIKE', "%$search%");
                });
            })->latest();

        return $this->applyPagination($query);
    }

    public function save(){
        $this->validate([
            'studentId'             => ['required'],
            'keteranganPelanggaran' => ['required','string','min:2'],
            'jenisPelanggaran'      => ['required','string','min:2','max:255'],
        ]);

        try{
            DB::beginTransaction();

            Violation::create([
                'student_id'      => $this->studentId,
                'teacher_id'      => $this->teacherId,
                'violation_type'  => $this->jenisPelanggaran,
                'description'     => $this->keteranganPelanggaran,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[tugas piket anda] ' .
                    auth()->user()->username .
                    ' gagal menambahkan tugas piket anda',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data pelanggaran siswa gagal ditambah.",
            ]);

            return redirect()->route('mobile.duty.create');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data pelanggaran siswa berhasil ditambah.",
        ]);

        return redirect()->route('mobile.duty.index');
    }

    public function mount(){
        $this->teacherId = auth()->user()->teacher->id;
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function muatUlang()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.mobile.duty.create');
    }
}
