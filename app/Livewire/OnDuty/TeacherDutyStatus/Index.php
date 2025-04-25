<?php

namespace App\Livewire\OnDuty\TeacherDutyStatus;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Teacher;
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
        'jenisKelamin' => '',
        'nomorPonsel' => '',
        'status' => '',
    ];

    public function changeDutyStatusMany(){
        $teachers = Teacher::whereIn('id', $this->selected)->get();
        $changeCount = $teachers->count();

        foreach ($teachers as $data) {
            $data->duty_status = !$data->duty_status;
            $data->save();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$changeCount data guru berhasil diubah.",
        ]);

        $this->reset();
        return redirect()->back();
    }

    public function changeDutyStatus($id){
        $teacher = Teacher::find($id);
        $teacher->duty_status = !$teacher->duty_status;
        $teacher->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Status piket guru {$teacher->name} berhasil diubah.",
        ]);

        $this->reset();
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
                if($status == 'piket'){
                    $query->where('duty_status', true);
                }

                if($status == 'tidak piket'){
                    $query->where('duty_status', false);
                }
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
        return view('livewire.on-duty.teacher-duty-status.index');
    }
}
