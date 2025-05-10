<?php

namespace App\Livewire\Student;

use App\Imports\StudentImport;
use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Student;
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

            Excel::import(new StudentImport, $this->fileExcel);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[import excel data siswa] ' .
                    auth()->user()->username .
                    ' gagal import data siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "import data siswa gagal dilakukan.",
            ]);

            $this->closeModal();
            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "import data siswa berhasil dilakukan.",
        ]);

        $this->closeModal();
        return redirect()->back();
    }

    public function deleteSelected()
    {
        $student = Student::whereIn('id', $this->selected)->get();
        $deleteCount = $student->count();

        foreach ($student as $data) {
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
            'detail' => "$deleteCount data siswa berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = Student::query()
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
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('nis', 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Student::all();
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
        return view('livewire.student.index');
    }
}
