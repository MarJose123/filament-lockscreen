<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Filament\Panel;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;

class Lockscreen  implements Plugin
{
    protected bool $hasEnabledLockscreen = false;

    public function getId(): string
    {
         return 'filament-lock-screen';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function register(Panel $panel): void
    {
        $panel
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('filament-lockscreen::default.user_menu_title'))
                    ->url(route("lockscreen.{$panel->getId()}.page"))
                    ->icon(config('filament-lockscreen.icon')),
            ]);
    }

    public function boot(Panel $panel): void
    {
        Livewire::component('LockerScreen', LockerScreen::class);

    }


}
