<?php

namespace App\Livewire\StudentParent;

use App\Imports\StudentParentImport;
use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\StudentParent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithFileUploads;

    public $filters = [
        'search' => '',
        'nomorPonsel' => '',
        'status' => '',
    ];

    public $show = false;
    public $fileExcel;

    public function showModal(){
        $this->show = true;
    }

    public function closeModal(){
        $this->reset([
            'fileExcel',
            'show',
        ]);
    }

    public function resetForm(){
        $this->reset([
            'fileExcel',
        ]);
    }

    public function importExcel(){

        try{
            DB::beginTransaction();

            Excel::import(new StudentParentImport, $this->fileExcel);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[import excel data orang tua siswa] ' .
                    auth()->user()->username .
                    ' gagal import data orang tua siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "import data orang tua siswa gagal dilakukan.",
            ]);

            $this->closeModal();
            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "import data orang tua siswa berhasil dilakukan.",
        ]);

        $this->closeModal();
        return redirect()->back();
    }

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

    #[On('muat-ulang')]
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

    public function muatUlang()
    {
        $this->dispatch('muat-ulang');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.student-parent.index');
    }
}
