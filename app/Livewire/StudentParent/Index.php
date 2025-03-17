<?php

namespace App\Livewire\StudentParent;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\StudentParent;
use Illuminate\Support\Facades\File;
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
        'nomorPonsel' => '',
        'status' => '',
    ];

    public function deleteSelected()
    {
        $student_parents = StudentParent::whereIn('id', $this->selected)->get();
        $deleteCount = $student_parents->count();

        foreach ($student_parents as $data) {
            if ($data->user->avatar) {
                File::delete(public_path('storage/' . $data->user->avatar));
            }
            $data->user->delete();
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data orang tua siswa berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[Computed()]
    public function rows()
    {
        $query = StudentParent::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['nomorPonsel'], function ($query, $nomorPonsel) {
                $query->where('phone_number', 'LIKE', "%$nomorPonsel%");
            })
            ->when($this->filters['status'], function ($query, $status) {
                $query->where('guardian_status', $status);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('name', 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return StudentParent::all();
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
        return view('livewire.student-parent.index');
    }
}
