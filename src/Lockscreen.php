<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Concerns\HasLockscreenConfiguration;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;

class Lockscreen implements Plugin
{
    use HasLockscreenConfiguration;

    public function getId(): string
    {
        return 'filament-lockscreen';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void
    {
        $panelId = $panel->getId();

        $panel->userMenuItems([
            Action::make('lockSession')
                ->label(__('filament-lockscreen::default.user_menu_title'))
                ->icon($this->getIcon())
                ->url(route("lockscreen.{$panelId}.lock-session"))
                ->postToUrl(),
        ]);

        Livewire::component('LockerScreen', LockerScreen::class);
    }
}
