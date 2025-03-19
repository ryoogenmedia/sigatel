<?php

namespace App\Livewire\Report;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\SchoolSubject as ModelsSchoolSubject;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SchoolSubject extends Component
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
        $query = ModelsSchoolSubject::query()
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
                $query->whereAny(['name', 'code'], 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return ModelsSchoolSubject::all();
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
        return view('livewire.report.school-subject');
    }
}
