<?php

namespace App\Livewire\Mobile\Home;

use App\Models\Violation;
use Livewire\Component;

class ParentHome extends Component
{
    public $filterTime = '';
    public $jmlPelanggaran;

    public function updatedFilterTime(){
        $this->getDataCounter();
    }

    public function getDataCounter(){
        $query = Violation::query()
            ->where('student_id', auth()->user()->parent->student->id);

        if($this->filterTime){
            switch($this->filterTime){
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;
                default:
                    break;
            }
        }

        $this->jmlPelanggaran = $query->count() ?? 0;
    }

    public function mount(){
        $this->getDataCounter();
    }

    public function render()
    {
        return view('livewire.mobile.home.parent-home');
    }
}
