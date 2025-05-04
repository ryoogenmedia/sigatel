<?php

namespace App\Livewire\Mobile\GrandAssignment;

use App\Models\Grade;
use App\Models\GradeAssignment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Tugas Kelas')]
    #[Layout('layouts.mobile-base')]

    public $teacherId;

    public $filters = [
        'search' => '',
    ];

    public function deleteData($id)
    {
        $gradeAssignment = GradeAssignment::find($id);
        $gradeAssignment->delete();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data tugas kelas berhasil disunting.",
        ]);

        return redirect()->route('mobile.grand-assignment.index');
    }

    #[Computed()]
    public function grade_assignments(){
        return GradeAssignment::query()
            ->when($this->filters['search'], function ($query) {
                $query->whereHas('grade', function ($query) {
                    $query->where('name', 'like', '%'.$this->filters['search'].'%');
                });
            })
            ->where('teacher_id', $this->teacherId)
            ->latest()
            ->get();
    }

    public function mount(){
        $this->teacherId = auth()->user()->teacher->id;
    }

    public function render()
    {
        return view('livewire.mobile.grand-assignment.index');
    }
}
