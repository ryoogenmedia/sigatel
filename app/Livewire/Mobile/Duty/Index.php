<?php

namespace App\Livewire\Mobile\Duty;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    #[Title('Piket')]
    #[Layout('layouts.mobile-base')]

    public $violationId;
    public $keterangan;

    public $filters = [
        'search' => '',
    ];

    public function getDataId($id){
        $violation = Violation::findOrFail($id);
        $this->violationId = $violation->id;
        $this->keterangan  = $violation->description;

        $this->dispatch('pushData', [
            'keterangan' => $this->keterangan,
        ]);
    }

    public function cancelData(){
        $this->reset([
            'keterangan',
            'violationId',
        ]);
    }

    public function deleteData(){
        $violation = Violation::findOrFail($this->violationId);
        $violation->delete();


        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data pelanggaran siswa berhasil dihapus.",
        ]);

        return redirect()->route('mobile.duty.index');
    }

    public function cancelDelete(){
        $this->reset(['violationId']);
    }

    #[Computed()]
    public function rows(){
        $query = Violation::query()
            ->when($this->filters['search'], function($query, $search){
                $query->whereHas('student', function($query) use ($search){
                    $query->where('name', 'LIKE',"%$search%");
                })->orWhereHas('teacher', function($query) use ($search){
                    $query->where('name', 'LIKE',"%$search%");
                });
            })->latest();

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
        return view('livewire.mobile.duty.index');
    }
}
