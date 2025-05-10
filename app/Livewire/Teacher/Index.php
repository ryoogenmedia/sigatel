<?php

namespace App\Livewire\Teacher;

use App\Imports\TeacherImport;
use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Teacher;
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
        'jenisKelamin' => '',
        'nomorPonsel' => '',
        'status' => '',
    ];

    public $show = false;
    public $fileExcel;

    public function showModal(){
        $this->show = true;
    }

    public function closeModal(){
        $this->show = false;
    }

    public function importExcel(){

        try{
            DB::beginTransaction();

            Excel::import(new TeacherImport, $this->fileExcel);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[import excel data guru] ' .
                    auth()->user()->username .
                    ' gagal import data guru',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "import data guru gagal dilakukan.",
            ]);

            $this->closeModal();
            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "import data guru berhasil dilakukan.",
        ]);

        $this->closeModal();
        return redirect()->back();
    }

    public function deleteSelected()
    {
        $teachers = Teacher::whereIn('id', $this->selected)->get();
        $deleteCount = $teachers->count();

        foreach ($teachers as $data) {
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
            'detail' => "$deleteCount data guru berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = Teacher::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['nomorPonsel'], function ($query, $nomorPonsel) {
                $query->where('phone_number', 'LIKE', "%$nomorPonsel%");
            })
            ->when($this->filters['status'], function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filters['jenisKelamin'], function ($query, $jenisKelamin) {
                $query->where('sex', $jenisKelamin);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('name', 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Teacher::all();
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
        return view('livewire.teacher.index');
    }
}
