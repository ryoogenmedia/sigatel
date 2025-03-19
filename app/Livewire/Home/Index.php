<?php

namespace App\Livewire\Home;

use App\Helpers\HomeChart;
use App\Models\OnDuty;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Teacher;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $period = "daily";
    public $totalStudent = 0;
    public $totalStudentParent = 0;
    public $totalTeacher = 0;

    public $onDutyApproved = [];
    public $onDutyPending = [];
    public $onDutyReject = [];

    public function getDataChart()
    {
        $this->totalStudent = HomeChart::TOTAL_DATA(Student::query(), $this->period);
        $this->totalStudentParent = HomeChart::TOTAL_DATA(StudentParent::query(), $this->period);
        $this->totalTeacher = HomeChart::TOTAL_DATA(Teacher::query(), $this->period);

        $this->onDutyApproved = HomeChart::CHART_DATA(OnDuty::query()
            ->where('status', 'approved'), $this->period);
        $this->onDutyPending = HomeChart::CHART_DATA(OnDuty::query()
            ->where('status', 'pending'), $this->period);
        $this->onDutyReject = HomeChart::CHART_DATA(OnDuty::query()
            ->where('status', 'reject'), $this->period);
    }

    public function getLoginHistories()
    {
        return  User::whereNotNull('last_login_time')
            ->orderBy('last_login_time', 'DESC')->limit(20)->get();
    }

    public function updatedPeriod()
    {
        $this->getDataChart();

        $date = $this->onDutyApproved['date'];
        $pending = $this->onDutyPending['data'];
        $reject = $this->onDutyReject['data'];
        $approved = $this->onDutyApproved['data'];

        $this->dispatch('updateChart', [
            'approved' => $approved,
            'pending' => $pending,
            'reject' => $reject,
            'date' => $date,
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
