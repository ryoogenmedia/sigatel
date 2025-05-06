<?php

namespace App\Livewire\Mobile\Assignment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\GradeAssignment;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithFileUploads;

    #[Title('Pemberian Tugas Kelas')]
    #[Layout('layouts.mobile-base')]

    public $gradeAssignmentId;
    public $alasanGuru;
    public $penjelasanTugas;
    public $tanggalPemberian;
    public $tanggalKumpul;
    public $fileDokumentasi;
    public $dokumentasi;

    public $filters = [
        'search' => '',
    ];

    public function getDataId($id){
        $gradeAssignment = GradeAssignment::findOrFail($id);
        $this->alasanGuru = $gradeAssignment->reason_teacher;
        $this->penjelasanTugas = $gradeAssignment->description;
        $this->tanggalPemberian = $gradeAssignment->schedule;
        $this->tanggalKumpul = $gradeAssignment->finish;
        $this->fileDokumentasi = $gradeAssignment->photoDocumentationUrl();
        $this->gradeAssignmentId = $id;

        $this->dispatch('pushData', [
            'alasanGuru' => $this->alasanGuru,
            'penjelasanTugas' => $this->penjelasanTugas,
            'tanggalPemberian' => $this->tanggalPemberian,
            'tanggalKumpul' => $this->tanggalKumpul,
            'dokumentasi' => $this->fileDokumentasi,
        ]);
    }

    public function addDokumentasi(){
        $this->validate([
            'dokumentasi' => ['required','image'],
        ]);

        $gradeAssignment = GradeAssignment::findOrFail($this->gradeAssignmentId);

        if($gradeAssignment->documentation_image){
            File::delete(public_path('storage/' . $gradeAssignment->documentation_image));
        }
        
        $gradeAssignment->update([
            'documentation_image' => $this->dokumentasi->store('foto-dokumentasi','public'),
        ]);

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Foto dokumentasi berhasil disunting.",
        ]);

        return redirect()->route('mobile.assignment.index');
    }

    public function cancelData(){
        $this->reset(['gradeAssignmentId','alasanGuru','penjelasanTugas']);
    }

    public function changeStatus(){
        $gradeAssignment = GradeAssignment::findOrFail($this->gradeAssignmentId);
        $gradeAssignment->status = !$gradeAssignment->status;
        $gradeAssignment->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Status pemberian berhasil disunting.",
        ]);

        return redirect()->route('mobile.assignment.index');
    }

    #[Computed()]
    public function rows(){
        $query = GradeAssignment::query()
            ->when($this->filters['search'], function($query, $search){
                $query->whereHas('grade', function($query) use ($search){
                    $query->where('name', 'LIKE', "%$search%");
                })->orWhereHas('teacher', function($query) use ($search){
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->orderBy('status', 'asc') // Prioritize rows with status false
            ->latest();

        return $query->get();
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
        return view('livewire.mobile.assignment.index');
    }
}
