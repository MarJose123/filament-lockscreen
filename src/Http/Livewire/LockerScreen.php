<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use Livewire\Component;

class LockerScreen extends Component
{

    public ?string $password = '';




    public function render()
    {
        return view('livewire.locker-screen');
    }
}
