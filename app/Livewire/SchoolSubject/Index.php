<?php

namespace App\Livewire\SchoolSubject;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\SchoolSubject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
    ];

    public $statusMapel;

    public function changeStatusMapel($id)
    {
        $schoolSubjects = SchoolSubject::findOrFail($id);
        $schoolSubjects->status = !$schoolSubjects->status;
        $schoolSubjects->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data status mata pelajaran berhasil dirubah.",
        ]);

        return redirect()->back();
    }

    public function deleteSelected()
    {
        $schoolSubjects = SchoolSubject::whereIn('id', $this->selected)->get();
        $deleteCount = $schoolSubjects->count();

        foreach ($schoolSubjects as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data mata pelajaran berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = SchoolSubject::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereAny(['name', 'code'], 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return SchoolSubject::all();
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
        $this->dispatch('muat-ulang');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.school-subject.index');
    }
}
