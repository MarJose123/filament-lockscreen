<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Icons\Heroicon;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Concerns\HasLockscreenConfiguration;
use lockscreen\FilamentLockscreen\Concerns\HasSwitch;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;

class Lockscreen implements Plugin
{
    use HasLockscreenConfiguration;
    use HasSwitch;

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
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function register(Panel $panel): void
    {
        if ($this->isPluginEnabled()) {
            Livewire::component('LockerScreen', LockerScreen::class);
            $panel->authMiddleware([Locker::class], true);
        }
    }

    public function boot(Panel $panel): void
    {
        if ($this->isPluginEnabled()) {
            $panelId = filament()->getCurrentPanel()->getId();

            $panel->userMenuItems([
                Action::make('lockSession')
                    ->label(fn(): string => __('filament-lockscreen::default.user_menu_title'))
                    ->icon($this->getIcon() ?? Heroicon::OutlinedLockClosed)
                    ->url(route("lockscreen.{$panelId}.lock-session"))
                    ->postToUrl(),
            ]);
        }
    }
}
