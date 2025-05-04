<?php

namespace App\Livewire\Mobile\Duty;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
{
    #[Title('Piket')]
    #[Layout('layouts.mobile-base')]

    public function render()
    {
        return view('livewire.mobile.duty.edit');
    }
}
