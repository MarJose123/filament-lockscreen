<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Livewire\Component;

class LockerScreen extends SimplePage
{
    use InteractsWithFormActions, WithRateLimiting;

    protected static ?string $title = null;

    protected ?string $maxContentWidth = 'full';

    public ?string $password = '';

    protected static string $view ='filament-lockscreen::page.auth.login' ;

    private ?string $account_username_field;

    private ?string $account_password_field;

    public function mount()
    {
        /*session(['lockscreen' => true]);
        if (! config('filament-lockscreen.enable_redirect_to')) {
            if (! session()->has('next') || session()->get('next') === null) {
                session(['next' => url()->previous()]);
            }
        }*/
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

    public function authenticate()
    {
        $data = $this->form->getState();
        $this->account_password_field = config('filament-lockscreen.table_columns.account_password_field');
        $this->account_username_field = config('filament-lockscreen.table_columns.account_username_field');
        /*
          *  Rate Limit
          */
        if (config('filament-lockscreen.rate_limit.enable_rate_limit')) {
            try {
                $this->rateLimit(config('filament-lockscreen.rate_limit.rate_limit_max_count', 5));
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

                if (config('filament-lockscreen.rate_limit.force_logout', false)) {
                    $this->forceLogout();
                    $panelId = filament()->getCurrentPanel()->getId();
                    return redirect()->route("filament.{$panelId}.auth.login");
                }
                $this->addError(
                    'password', __('filament::login.messages.throttled', [
                        'seconds' => $exception->secondsUntilAvailable,
                        'minutes' => ceil($exception->secondsUntilAvailable / 60),
                    ]));

                return null;
            }
        }

        if (! Filament::auth()->attempt([
            $this->account_username_field => Filament::auth()->user()->{$this->account_username_field},
            $this->account_password_field => $data['password'],
        ])) {
            $this->addError('password', __('filament::login.messages.failed'));

            return null;
        }

        // redirect to the main page and forge the lockscreen session
        session()->forget('lockscreen');
        session()->regenerate();
        if (config('filament-lockscreen.enable_redirect_to')) {
            return redirect()->route(config('filament-lockscreen.redirect_route'));
        }
        // store to variable
        $url = session()->get('next');
        // remove the value
        session()->forget('next');

        return redirect($url);
    }

    protected function getFormSchema(): array
    {
        return[
            TextInput::make('password')
                ->label(__('filament-lockscreen::default.fields.password'))
                ->password()
                ->autocomplete(false)
                ->required(),
        ];
    }

    public function getTitle(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return static::$title ?? (string) str(__('filament-lockscreen::default.heading'))
            ->kebab()
            ->replace('-', ' ')
            ->title();
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('filament-panels::pages/auth/login.form.actions.authenticate.label'))
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




}
