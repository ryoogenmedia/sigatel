<?php

namespace App\Livewire\OnDuty\Feedback;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\OnDuty;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'scheduleTime' => '',
        'finishTime' => '',
        'category' => '',
    ];

    public $showModal = false;
    public $checkedStatus = false;

    public $statusOnDuty;
    public $onDutyId;

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function openModal($id)
    {
        $onDuty = OnDuty::findOrFail($id);
        $this->showModal = true;
        $this->onDutyId = $onDuty->id;
        $this->statusOnDuty = $onDuty->status;
    }

    public function resetForm()
    {
        $this->reset([
            'statusOnDuty',
        ]);
    }

    public function changeStatusOnDuty()
    {
        $onDuty = OnDuty::findOrFail($this->onDutyId);

        $onDuty->status = $this->statusOnDuty;
        $onDuty->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data status piket berhasil dirubah.",
        ]);

        $this->closeModal();
        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = OnDuty::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['scheduleTime'], function ($query, $scheduleTime) {
                $query->whereDate('schedule_time', '<=', Carbon::parse($scheduleTime)->format('Y-m-d'));
            })
            ->when($this->filters['finishTime'], function ($query, $finishTime) {
                $query->where('finish_time', '>=', Carbon::parse($finishTime)->format('Y-m-d H:i:s'));
            })
            ->when($this->filters['category'], function ($query, $category) {
                $query->where('violation_type', $category);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereHas('student', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })
            ->when($this->checkedStatus, function ($query, $status) {
                if ($status == false) {
                    $query->where('status', 'pending');
                } else {
                    $query->where('status', 'approved');
                }
            })
            ->orderBy('status');

        if ($this->checkedStatus == false) {
            $query->where('status', 'pending');
        } else {
            $query->where('status', 'approved');
        }

        return $this->applyPagination($query->latest());
    }

    #[Computed()]
    public function allData()
    {
        return OnDuty::all();
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
    }

    public function render()
    {
        return view('livewire.on-duty.feedback.index');
    }
}
