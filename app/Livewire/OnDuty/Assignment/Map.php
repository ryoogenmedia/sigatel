<?php

namespace App\Livewire\OnDuty\Assignment;

use App\Models\OnDuty;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Map extends Component
{
    public $mapMarker;
    public $onDutyId;
    public $longitude;
    public $latitude;

    public $onDutyLocation;

    public function resetLocation()
    {
        $this->reset(['longitude', 'latitude']);
        $this->dispatch('resetLocation');
    }

    #[On('changeLocation')]
    public function changeLocation($longitude, $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude  = $latitude;
    }

    public function save()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'longitude' => ['required'],
                'latitude'  => ['required'],
            ]);

            $onDuty = OnDuty::findOrFail($this->onDutyId);

            $onDuty->update([
                'longitude' => $this->longitude,
                'latitude'  => $this->latitude,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[lokasi penugasan] ' .
                    auth()->user()->username .
                    ' gagal menambahkan lokasi',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data lokasi gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data lokasi berhasil disunting.",
        ]);

        return redirect()->route('on-duty.assignment.index');
    }

    public function mount($id)
    {
        $onDuty                 = OnDuty::findOrFail($id);
        $this->onDutyLocation   = $onDuty->only(['id', 'latitude', 'longitude']);
        $this->mapMarker        = asset('ryoogenmedia/map-marker.png');

        $this->onDutyId     = $onDuty->id;
        $this->longitude    = $onDuty->longitude;
        $this->latitude     = $onDuty->latitude;
    }

    public function render()
    {
        return view('livewire.on-duty.assignment.map');
    }
}
