<?php

namespace App\Livewire\OnDuty\Feedback;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\FeedBack;
use App\Models\OnDuty;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Message extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
    ];

    public $onDutyId;
    public $pesan;
    public $feedBackId;


    public function save()
    {
        if ($this->feedBackId) {
            $feedBack = FeedBack::findOrFail($this->feedBackId);

            $feedBack->update([
                'on_duty_id' => $this->onDutyId,
                'comment' => $this->pesan,
            ]);
        } else {
            FeedBack::create([
                'on_duty_id' => $this->onDutyId,
                'comment' => $this->pesan,
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "pesan berhasil ditambah.",
        ]);

        $this->resetData();
        return redirect()->back();
    }

    public function suntingData($id)
    {
        $feedBack = FeedBack::findOrFail($id);
        $this->feedBackId = $feedBack->id;
    }

    public function resetData()
    {
        $this->resetExcept(['onDutyId']);
    }

    public function deleteSelected()
    {
        $feedbacks = FeedBack::whereIn('id', $this->selected)->get();
        $deleteCount = $feedbacks->count();

        foreach ($feedbacks as $data) {
            $data->delete();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data masukan siswa piket berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[Computed()]
    public function rows()
    {
        $query = FeedBack::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('comment', 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return FeedBack::all();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function mount($id)
    {
        $onDuty = OnDuty::findOrFail($id);
        $this->onDutyId = $onDuty->id;
    }

    public function render()
    {
        return view('livewire.on-duty.feedback.message');
    }
}
