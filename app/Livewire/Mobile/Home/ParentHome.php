<?php

namespace App\Livewire\Mobile\Home;

use App\Models\Violation;
use Livewire\Component;

class ParentHome extends Component
{
    public $filterTime = '';
    public $jmlPelanggaran;
    public $violationsPercent;

    public function updatedFilterTime(){
        $this->getDataCounter();
    }

    public function getDataCounter(){
        $violation = Violation::query()
            ->where('student_id', auth()->user()->parent->student->id);

        if($this->filterTime){
            switch($this->filterTime){
                case 'today':
                    $violation->whereDate('created_at', now()->toDateString());
                    break;
                case 'this_week':
                    $violation->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $violation->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'this_year':
                    $violation->whereYear('created_at', now()->year);
                    break;
                default:
                    break;
            }
        }

        $this->jmlPelanggaran = $violation->count() ?? 0;

        $totalViolations = Violation::where('student_id', auth()->user()->parent->student->id)->count();

        $this->violationsPercent = $totalViolations > 0
            ? round(($this->jmlPelanggaran / (365)) * 100, 0)
            : 0;

        if ($this->jmlPelanggaran === 0) {
            $this->violationsPercent = 0;
        }

        $this->dispatch('updatedPercent', [
            'percent' => $this->violationsPercent,
        ]);
    }

    public function mount(){
        $this->getDataCounter();
    }

    public function render()
    {
        return view('livewire.mobile.home.parent-home');
    }
}
