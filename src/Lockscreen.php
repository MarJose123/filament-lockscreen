<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;

class Lockscreen implements Plugin
{
    protected bool $hasEnabledLockscreen = false;

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

        $panel->userMenuItems([
            'lockscreen' => MenuItem::make()
                ->label(fn () => __('filament-lockscreen::default.user_menu_title'))
                ->postAction(route("lockscreen.{$panel->getId()}.lock-session"))
                ->icon(config('filament-lockscreen.icon')),
        ]);

        Livewire::component('LockerScreen', LockerScreen::class);
    }
}
