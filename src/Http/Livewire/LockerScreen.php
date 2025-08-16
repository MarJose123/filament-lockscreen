<?php

namespace lockscreen\FilamentLockscreen\Http\Livewire;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Exceptions\NoDefaultPanelSetException;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use lockscreen\FilamentLockscreen\Lockscreen;

/**
 * @property-read Schema $form
 */
class LockerScreen extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected bool $hasTopbar = false;

    protected static ?string $title = null;

    protected string|Width|null $maxContentWidth = Width::Medium;

    protected ?string $heading = '';

    protected string $view = 'filament-lockscreen::page.auth.login';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    /**
     * @throws NoDefaultPanelSetException
     */
    public function mount(): void
    {
        if (! Filament::auth()->check()) {
            if (filament()->getCurrentPanel()) {
                redirect()->intended(filament()->getCurrentPanel()->getLoginUrl());
            }

            redirect()->intended(filament()->getDefaultPanel()->getLoginUrl());
        }

        if (! session()->has('lockscreen')) {
            if (session()->has('url.intended')) {
                redirect()->intended();
            }

            redirect()->intended(filament()->getDefaultPanel()->getPath());
        }

    }

    public function authenticate(): Redirector|RedirectResponse|Application|null
    {

        if (Lockscreen::get()->isRateLimitEnabled()) {
            try {
                $this->rateLimit(Lockscreen::get()->getRateLimitLimit());
            } catch (TooManyRequestsException $exception) {
                $this->getRateLimitedNotification($exception)->send();

                if (Lockscreen::get()->isForceLogout()) {
                    $this->forceLogout();
                    $panelId = filament()->getCurrentPanel()->getId();

                    return to_route("filament.{$panelId}.auth.login");
                }

                return null;
            }
        }

        $this->form->getState();

        if (! Filament::auth()->attempt([
            Lockscreen::get()->getCustomTableColumns()[0] => Filament::auth()->user()->{Lockscreen::get()->getCustomTableColumns()[0]},
            Lockscreen::get()->getCustomTableColumns()[1] => $this->data['password'],
        ])) {
            $this->addError('data.password', __('filament-panels::auth/pages/login.messages.failed'));

            return null;
        }

        // redirect to the main page and forge the lockscreen session
        session()->regenerate();
        $this->purgeSession();

        return redirect()->intended();
    }

    protected function purgeSession(): void
    {
        session()->forget('lockscreen');
        session()->forget('session_last_activity');
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title(__('filament-panels::auth/pages/login.notifications.throttled.title'))
            ->body(array_key_exists('body', __('filament-panels::auth/pages/login.notifications.throttled') ?: []) ? __('filament-panels::auth/pages/login.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
            ]) : null)
            ->danger();
    }

    protected function forceLogout(): void
    {
        Notification::make()
            ->title(__('filament-lockscreen::default.notification.title'))
            ->body(__('filament-lockscreen::default.notification.message'))
            ->danger()
            ->send();

        filament()->getCurrentPanel()->auth()->logout();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarComponent(),
                $this->getUserNameFormComponent(),
                $this->getPasswordFormComponent(),
            ]);

    }

    protected function getAvatarComponent(): Component
    {
        /** @var User $user */
        $user = \filament()->getCurrentPanel()->auth()->user();

        return ImageEntry::make('avatar')
            ->hiddenLabel()
            ->circular()
            ->imageSize(80)
            ->columnSpanFull()
            ->defaultImageUrl((new UiAvatarsProvider)->get($user))
            ->state(method_exists($user, 'getFilamentAvatarUrl') ? $user->getFilamentAvatarUrl() : (new UiAvatarsProvider)->get($user))
            ->extraAttributes([
                'style' => 'justify-content: center;',
            ]);

    }

    protected function getUserNameFormComponent(): Component
    {
        return
            TextEntry::make('username')
                ->hiddenLabel()
                ->id('username')
                ->columnSpanFull()
                ->extraAttributes([
                    'style' => 'justify-content: center; display: flex;',
                ])
                ->state(Lockscreen::get()->isDisplayNameEnabled() ? filament()->getCurrentPanel()->auth()->user()->{Lockscreen::get()->displayName()} : '');
    }

    protected function getPasswordFormComponent(): Component
    {
        return
            TextInput::make('password')
                ->name('password')
                ->id('password')
                ->label(__('filament-lockscreen::default.fields.password'))
                ->password()
                ->autocomplete(false)
                ->autofocus()
                ->rule([
                    'required',
                ])
                ->extraInputAttributes(['tabindex' => 1])
                ->hint(
                    new HtmlString(Blade::render(
                        '<x-filament::link icon="heroicon-o-arrows-right-left" x-on:click="event.preventDefault(); document.getElementById(\'logout-form\').submit();" tabindex="3"  weight="normal" style="cursor: default; text-decoration:underline" color="gray"> {{ __(\'filament-lockscreen::default.button.switch_account\') }}</x-filament::link>')
                    ));
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

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('authenticate')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment(Alignment::End)
                    ->fullWidth(),
            ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('data');
    }

    public function getHeading(): string|Htmlable
    {
        return __('filament-lockscreen::default.heading');
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
