<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Exceptions\NoDefaultPanelSetException;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use lockscreen\FilamentLockscreen\Lockscreen;

class LockerScreen extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected bool $hasTopbar = false;

    protected static ?string $title = null;

    protected string|Width|null $maxContentWidth = Width::Full;

    protected ?string $heading = '';

    protected string $view = 'filament-lockscreen::page.auth.login';


    /**
     * @return \Illuminate\Foundation\Application|RedirectResponse|Redirector|object|void
     *
     * @throws NoDefaultPanelSetException
     */
    public function mount()
    {
        /**
         * Authentication Check
         *
         * Check if the request is still authenticated or not before rendering the page,
         * if not authenticated, then redirect to the login page of the current panel, or default panel if the current panel could not be detected.
         */
        if (! Filament::auth()->check()) {
            if (filament()->getCurrentPanel()) {
                return redirect(filament()->getCurrentPanel()->getLoginUrl());
            }

            return redirect(filament()->getDefaultPanel()->getLoginUrl());
        }

        /**
         * Redirect to the filament default home url if the session is not locked
         */
        if (! session()->has('lockscreen')) {
            if (session()->has('url.intended')) {
                return redirect()->intended();
            }

            return redirect(filament()->getDefaultPanel()->getPath());
        }
    }

    public function authenticate(): Redirector|RedirectResponse|Application|null
    {
        $data = $this->form->getState();
        /*
          *  Rate Limit
          */
        if (config('filament-lockscreen.rate_limit.enable_rate_limit', true)) {
            try {
                $this->rateLimit(Lockscreen::get()->getRateLimitLimit());
            } catch (TooManyRequestsException $exception) {
                Notification::make()
                    ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                        'seconds' => $exception->secondsUntilAvailable,
                        'minutes' => ceil($exception->secondsUntilAvailable / 60),
                    ]))
                    ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                        'seconds' => $exception->secondsUntilAvailable,
                        'minutes' => ceil($exception->secondsUntilAvailable / 60),
                    ]) : null)
                    ->danger()
                    ->send();

                if (Lockscreen::get()->isForceLogout()) {
                    $this->forceLogout();
                    $panelId = filament()->getCurrentPanel()->getId();

                    return to_route("filament.{$panelId}.auth.login");
                }

                return null;
            }
        }

        if (! Filament::auth()->attempt([
            Lockscreen::get()->getCustomTableColumns()[0] => Filament::auth()->user()->{Lockscreen::get()->getCustomTableColumns()[0]},
            Lockscreen::get()->getCustomTableColumns()[1] => $data['password'],
        ])) {
            $this->addError('password', __('filament-panels::pages/auth/login.messages.failed'));

            return null;
        }

        // redirect to the main page and forge the lockscreen session
        session()->regenerate();
        session()->forget('lockscreen');
        session()->forget('session_last_activity');

        return redirect()->intended();
    }

    protected function forceLogout(): void
    {
        filament()->getCurrentPanel()->auth()->logout();

        Notification::make()
            ->title(__('filament-lockscreen::default.notification.title'))
            ->body(__('filament-lockscreen::default.notification.message'))
            ->danger()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('password')
                ->label(__('filament-lockscreen::default.fields.password'))
                ->password()
                ->autocomplete(false)
                ->required(),
        ];
    }

    public function getTitle(): Htmlable|string
    {
        return (string) str(__('filament-lockscreen::default.heading'))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    public function hasLogo(): bool
    {
        return false;
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('filament-lockscreen::default.button.submit_label'))
            ->submit('authenticate');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
