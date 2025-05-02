<?php

namespace App\Livewire\Mobile\Setting\Profile;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Pengaturan Akun')]
    #[Layout('layouts.mobile-base')]

    public function render()
    {
        return view('livewire.mobile.setting.profile.index');
    }
}
