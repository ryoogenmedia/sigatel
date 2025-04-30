<?php

namespace App\Livewire\OnDuty\GradeAssignment;

use App\Models\GradeAssignment;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class Map extends Component
{
    public $mapMarker;
    public $gradeAssignmentId;
    public $longitude;
    public $latitude;

    public $gradeAssignmentLocation;

    public function resetLocation()
    {
        $this->reset(['longitude', 'latitude']);
        $this->dispatch('resetLocation');
    }

    #[On('changeLocation')]
    public function changeLocation($longitude, $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function save()
    {
        try {
            DB::beginTransaction();

            $this->validate([
                'longitude' => ['required'],
                'latitude' => ['required'],
            ]);

            $onDuty = GradeAssignment::findOrFail($this->gradeAssignmentId);

            $onDuty->update([
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[lokasi penugasan kelas] ' .
                    auth()->user()->username .
                    ' gagal menambahkan lokasi penugasan kelas',
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

        return redirect()->route('on-duty.grade-assignment.index');
    }

    public function mount($id)
    {
        $gradeAssignment                = GradeAssignment::findOrFail($id);
        $this->gradeAssignmentLocation  = $gradeAssignment->only(['id', 'latitude', 'longitude']);
        $this->mapMarker                = asset('ryoogenmedia/map-marker.png');

        $this->gradeAssignmentId    = $gradeAssignment->id;
        $this->longitude            = $gradeAssignment->longitude;
        $this->latitude             = $gradeAssignment->latitude;
    }

    public function render()
    {
        return view('livewire.on-duty.grade-assignment.map');
    }
}
