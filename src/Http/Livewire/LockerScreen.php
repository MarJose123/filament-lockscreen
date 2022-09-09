<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Phpsa\FilamentPasswordReveal\Password;

class LockerScreen extends Component implements HasForms
{
    use InteractsWithForms;

    public ?string $password = '';

    public function mount()
    {
        session(['lockscreen' => true]);
    }

    public function login()
    {
        $data = $this->form->getState();
        ddd($data);

    }

    protected function getFormSchema(): array
    {
        return[
            Password::make('password')
                ->label('Password')
                ->required(),
        ];
    }


    public function render()
    {
        return view('filament-lockscreen::livewire.locker-screen')
            ->layout('filament::components.layouts.base', [
                'title' => __('filament::login.title'),
            ]);
    }
}
