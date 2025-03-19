<?php

namespace App\Livewire\Grade;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Grade;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
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

    public $namaKelas;
    public $nomorLantai;
    public $waliKelas;

    public $gradeId;

    public function getGrade($id)
    {
        $grade = Grade::findOrFail($id);

        $this->gradeId = $grade->id;
        $this->namaKelas = $grade->name;
        $this->nomorLantai = $grade->floor;
        $this->waliKelas = $grade->teacher->id;

        $this->dispatch('load-data-grade');
    }

    public function save()
    {
        $this->validate([
            'namaKelas' => ['required', 'string', 'min:2', 'max:255'],
            'nomorLantai' => ['required'],
            'waliKelas' => ['required']
        ]);

        try {
            DB::beginTransaction();

            if ($this->gradeId) {
                $grade = Grade::findOrFail($this->gradeId);

                $grade->update([
                    'teacher_id' => $this->waliKelas,
                    'name' => $this->namaKelas,
                    'floor' => $this->nomorLantai,
                ]);
            } else {
                Grade::create([
                    'teacher_id' => $this->waliKelas,
                    'name' => $this->namaKelas,
                    'floor' => $this->nomorLantai,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[kelas] ' .
                    auth()->user()->username .
                    'gagal' . $this->gradeId ? 'menyunting' : 'menambah' . 'kelas',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => 'Data kelas gagal ' . ($this->gradeId ? 'diperbarui' : 'ditambahkan') . '.',
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => 'Data kelas berhasil ' . ($this->gradeId ? 'diperbarui' : 'ditambahkan') . '.',
        ]);

        $this->resetData();
        return redirect()->back();
    }

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

    #[On('load-data-grade')]
    #[Computed()]
    public function teachers()
    {
        $id = "";

        if ($this->gradeId) {
            $grade = Grade::findOrFail($this->gradeId);
            $id = $grade->teacher_id;
        }

        return Teacher::query()
            ->whereDoesntHave('grade')
            ->where('status', 'aktif')
            ->orWhere('id', $id)
            ->get(['id', 'name']);
    }

    #[On('load-data-grade')]
    #[On('muat-ulang')]
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
            })->when($this->gradeId, function ($query, $id) {
                $query->where('id', $id);
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

    public function resetData()
    {
        $this->reset([
            'namaKelas',
            'nomorLantai',
            'waliKelas',
            'gradeId',
        ]);
    }

    public function muatUlang()
    {
        $this->dispatch('muat-ulang');
    }

    public function render()
    {
        return view('livewire.grade.index');
    }
}
