<?php

namespace App\Livewire\Mobile\Home;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Beranda')]
    #[Layout('layouts.mobile-base')]

    public function render()
    {
        return view('livewire.mobile.home.index');
    }
}
