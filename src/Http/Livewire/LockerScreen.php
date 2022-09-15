<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Livewire\Component;

class LockerScreen extends Component implements HasForms
{
    use InteractsWithForms, WithRateLimiting;

    public ?string $password = '';

    public function mount()
    {
        session(['lockscreen' => true]);
    }

    public function doRateLimit()
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->addError(
                'password', __('filament::login.messages.throttled', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil($exception->secondsUntilAvailable / 60),
            ]));

            return null;
        }
    }

    public function login()
    {
        $data = $this->form->getState();
        $this->doRateLimit();

        if (! Filament::auth()->attempt([
            'email' =>  Filament::auth()->user()->email,
            'password' => $data['password']
        ])) {
            $this->addError('password', __('filament::login.messages.failed'));
            return null;
        }
        // redirect to the main page and forge the lockscreen session
        session()->forget('lockscreen');
        session()->regenerate();
        return redirect(config('filament.home_url'));

    }

    protected function getFormSchema(): array
    {
        return[
            app(config('filament-lockscreen.fields.password'), TextInput::class)::make('password')
                ->password()
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
