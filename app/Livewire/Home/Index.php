<?php

namespace App\Livewire\Home;

use App\Helpers\HomeChart;
use App\Models\GradeAssignment;
use App\Models\OnDuty;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $period              = "daily";
    public $totalStudent        = 0;
    public $totalStudentParent  = 0;
    public $totalTeacher        = 0;

    public $gradeAssignmentApprove      = [];
    public $gradeAssignmentNotApprove   = [];

    public function getDataChart()
    {
        $this->totalStudent       = HomeChart::TOTAL_DATA(Student::query(), $this->period);
        $this->totalStudentParent = HomeChart::TOTAL_DATA(StudentParent::query(), $this->period);
        $this->totalTeacher       = HomeChart::TOTAL_DATA(Teacher::query(), $this->period);

        $this->gradeAssignmentApprove = HomeChart::CHART_DATA(GradeAssignment::query()
            ->where('status', true), $this->period);

        $this->gradeAssignmentNotApprove = HomeChart::CHART_DATA(GradeAssignment::query()
            ->where('status', false), $this->period);
    }

    public function getLoginHistories()
    {
        return  User::whereNotNull('last_login_time')
            ->orderBy('last_login_time', 'DESC')->limit(20)->get();
    }

    public function updatedPeriod()
    {
        $this->getDataChart();

        $date       = $this->gradeAssignmentApprove['date'];
        $pending    = $this->gradeAssignmentNotApprove['data'];
        $approved   = $this->gradeAssignmentApprove['data'];

        $this->dispatch('updateChart', [
            'approved'  => $approved,
            'pending'   => $pending,
            'date'      => $date,
        ]);
    }

    public function mount()
    {
        $this->getDataChart();
    }

    public function render()
    {
        return view('livewire.home.index', [
            'login_history' => $this->getLoginHistories(),
        ]);
    }
}
