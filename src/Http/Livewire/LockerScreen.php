<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;
use Phpsa\FilamentPasswordReveal\Password;

class LockerScreen extends Component implements HasForms
{
    use InteractsWithForms, WithRateLimiting;

    public ?string $password = '';

    public function mount()
    {
        session(['lockscreen' => true]);
        if(!config('filament-lockscreen.enable_redirect_to'))
            if(!session()->has('next') || session()->get('next') === null )
            {
                session(['next' => url()->previous()]);
            }
    }

    public function doRateLimit()
    {
        try {
            $this->rateLimit(config('filament-lockscreen.rate_limit.rate_limit_max_count', 5));
        } catch (TooManyRequestsException $exception) {


            if(config('filament-lockscreen.rate_limit.force_logout', false))
            {
               $this->forceLogout();
                return redirect(url(config('filament.path')));
            }
            $this->addError(
                'password', __('filament::login.messages.throttled', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil($exception->secondsUntilAvailable / 60),
            ]));
            return null;

        }
    }

    protected function forceLogout()
    {
        Filament::auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        Notification::make()
            ->title(__('filament-lockscreen::default.notification.title'))
            ->body(__('filament-lockscreen::default.notification.message'))
            ->danger()
            ->send();
    }

    public function login()
    {
        $data = $this->form->getState();

        if (! Filament::auth()->attempt([
            'email' =>  Filament::auth()->user()->email,
            'password' => $data['password']
        ])) {
            $this->addError('password', __('filament::login.messages.failed'));
        }

        /*
        *  Rate Limit
        */
        if(config('filament-lockscreen.rate_limit.enable_rate_limit'))
        {
            return  $this->doRateLimit();
        }

        // redirect to the main page and forge the lockscreen session
        session()->forget('lockscreen');
        session()->regenerate();
        if(config('filament-lockscreen.enable_redirect_to')) return redirect()->route(config('filament-lockscreen.redirect_route'));
        // store to variable
        $url = session()->get('next');
        // remove the value
        session()->forget('next');
        return redirect($url);
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
