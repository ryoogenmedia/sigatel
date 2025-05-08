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

    #[Computed()]
    public function rows(){
        $query = Violation::query()
            ->where('student_id', auth()->user()->parent->student_id)
            ->latest();

        return $query->get();
    }

    public function render()
    {
        return view('livewire.mobile.student-violation.index');
    }
}
