<?php

namespace App\Livewire\Grade;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Grade;
use Livewire\Attributes\Computed;
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

    public function deleteSelected()
    {
        $grades = Grade::whereIn('id', $this->selected)->get();
        $deleteCount = $grades->count();

        foreach ($grades as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data kelas berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[Computed()]
    public function rows()
    {
        $query = Grade::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereAny(['name', 'floor'], 'LIKE', "%$search%")
                    ->orWhereHas('teacher', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    });
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Grade::all();
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
        return view('livewire.grade.index');
    }
}
