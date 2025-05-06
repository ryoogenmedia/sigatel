<?php

namespace App\Livewire\Mobile\Home;

use App\Models\GradeAssignment;
use App\Models\Violation;
use Livewire\Component;

class TeacherHome extends Component
{
    public $jmlSiswaMelanggar;
    public $jmlPenugasanKelas;
    public $jmlSiswaAnda;

    public function getDataCounter(){
        $user = auth()->user();
        $this->jmlSiswaMelanggar = Violation::count();
        $this->jmlPenugasanKelas = GradeAssignment::count();
        $grade = $user->teacher->grade;
        $this->jmlSiswaAnda = $grade->students->count();
    }

    public function mount(){
        $this->getDataCounter();
    }

    public function render()
    {
        return view('livewire.mobile.home.teacher-home');
    }
}
