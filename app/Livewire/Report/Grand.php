<?php

namespace App\Livewire\Report;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Grade;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Grand extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'date_start' => '',
        'date_end' => '',
    ];

    #[Computed()]
    public function rows()
    {
        $query = Grade::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['date_start'], function ($query) {
                $dateStart = $this->filters['date_start'] . '-01';
                $query->whereDate('created_at', '>=', $dateStart);
            })
            ->when($this->filters['date_end'], function ($query) {
                $dateEnd = date('Y-m-t', strtotime($this->filters['date_end'] . '-01'));
                $query->whereDate('created_at', '<=', $dateEnd);
            })
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
        return view('livewire.report.grand');
    }
}
