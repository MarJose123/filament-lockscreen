<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
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
       /* if (Auth::attempt(['username' => Filament::auth()->user()->username, 'password' => $data['password']])) {
            session()->regenerate();
            $user = Auth::user();
            session()->forget('lockscreen');
            Notification::make()
                ->title('Successfully login')
                ->success()
                ->send();
            return redirect()->route(config('filament.home_url'));
        }*/
        Notification::make()
            ->title('Invalid password')
            ->success()
            ->send();
        return redirect()->route('lockscreenpage');

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
