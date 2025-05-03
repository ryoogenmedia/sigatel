<?php

namespace App\Livewire\Mobile\Grade;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Kelas')]
    #[Layout('layouts.mobile-base')]

    public $students;
    public $grade;

    public $totalSiswa;
    public $totalSiswaMelanggar;

    public function mount()
    {
        $user = auth()->user();
        $this->grade = $user->teacher->grade;
        $this->students = $this->grade->students;
        $this->totalSiswa = $this->grade->students->count();
        $this->totalSiswaMelanggar = $this->students->filter(function ($student) {
            return $student->violations()->whereDate('created_at', today())->exists();
        })->count();
    }

    public function render()
    {
        return view('livewire.mobile.grade.index');
    }
}
