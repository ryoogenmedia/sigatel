<?php

namespace App\Livewire\User;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\User;
use Illuminate\Support\Facades\File;
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
        'roles' => '',
    ];

    public function deleteSelected()
    {
        $users = User::whereIn('id', $this->selected)->get();
        $deleteCount = $users->count();

        foreach ($users as $data) {
            if ($data->avatar) {
                File::delete(public_path('storage/' . $data->avatar));
            }
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data pengguna berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = User::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['roles'], function ($query, $roles) {
                $query->where('roles', $roles);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereAny(['username', 'roles', 'email'], 'LIKE', "%$search%");
            })->orderBy('roles')->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return User::all();
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
        return view('livewire.user.index');
    }
}
