<?php

namespace App\Livewire\OnDuty\Violation;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Violation;
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
        'search'     => '',
        'type'       => '',
        'date_start' => '',
        'date_end'   => '',
    ];

    #[Computed()]
    public function getViolationType(){
        return Violation::select('violation_type')
            ->distinct()
            ->get();
    }

    public function deleteSelected()
    {
        $violation = Violation::whereIn('id', $this->selected)->get();
        $deleteCount = $violation->count();

        foreach ($violation as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data pelanggaran siswa berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = Violation::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereHas('student', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })->orWhereHas('teacher', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->when($this->filters['type'], function ($query, $type) {
                $query->where('violation_type', $type);
            })
            ->when($this->filters['date_start'], function ($query, $date_start) {
                $query->whereDate('created_at', '>=', $date_start);
            })
            ->when($this->filters['date_end'], function ($query, $date_end) {
                $query->whereDate('created_at', '<=', $date_end);
            })
            ->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Violation::all();
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
        return view('livewire.on-duty.violation.index');
    }
}
