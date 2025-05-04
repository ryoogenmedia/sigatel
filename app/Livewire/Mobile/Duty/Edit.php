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

class Edit extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    #[Title('Sunting Pelanggran Siswa')]
    #[Layout('layouts.mobile-base')]

    public $violationId;
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

        $this->studentId   = $student->id;
        $this->studentName = $student->name;
        $this->studentNIS  = $student->nis;
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

    public function edit(){
        $this->validate([
            'studentId'             => ['required'],
            'keteranganPelanggaran' => ['required','string','min:2'],
            'jenisPelanggaran'      => ['required','string','min:2','max:255'],
        ]);

        try{
            DB::beginTransaction();

            $violation = Violation::findOrFail($this->violationId);

            $violation->update([
                'student_id'      => $this->studentId,
                'teacher_id'      => $this->teacherId,
                'violation_type'  => $this->jenisPelanggaran,
                'description'     => $this->keteranganPelanggaran,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[sunting pelanggran siswa] ' .
                    auth()->user()->username .
                    ' gagal menyunting tugas siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data pelanggaran siswa gagal disunting.",
            ]);

            return redirect()->route('mobile.duty.edit');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data pelanggaran siswa berhasil disunting.",
        ]);

        return redirect()->route('mobile.duty.index');
    }

    public function mount($id){
        $this->teacherId = auth()->user()->teacher->id;
        $violation       = Violation::findOrFail($id);

        $this->violationId           = $violation->id;
        $this->teacherId             = $violation->teacher->id;
        $this->studentId             = $violation->student->id;
        $this->studentName           = $violation->student->name;
        $this->studentNIS            = $violation->student->nis;
        $this->keteranganPelanggaran = $violation->description;
        $this->jenisPelanggaran      = $violation->violation_type;
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
        return view('livewire.mobile.duty.edit');
    }
}
