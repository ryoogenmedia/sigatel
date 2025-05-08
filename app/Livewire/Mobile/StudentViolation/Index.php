<?php

namespace App\Livewire\Mobile\StudentViolation;

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Pelanggaran Anak Anda')]
    #[Layout('layouts.mobile-base')]

    public $filters = [
        'timeline' => '',
    ];

    #[Computed()]
    public function rows(){
        $query = Violation::query()
            ->when($this->filters['timeline'], function($query, $time){
                switch($time){
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
            })
            ->where('student_id', auth()->user()->parent->student_id)
            ->latest();

        return $query->get();
    }

    public function render()
    {
        return view('livewire.mobile.student-violation.index');
    }
}
