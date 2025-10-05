<?php

use Filament\Facades\Filament;
use Filament\Facades\Filament\Panel;
use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\LockscreenSessionController;
use lockscreen\FilamentLockscreen\Lockscreen;

if (Lockscreen::get()->isPluginEnabled()) {
    Route::name('lockscreen.')
        ->group(function (): void {
            foreach (Filament::getPanels() as $panel) {
                $panelId = $panel->getId();
                $domains = $panel->getDomains();

                foreach ((blank($domains) ? [null] : $domains) as $domain) {
                    Route::domain($domain)
                        ->middleware(...$panel->getMiddleware())
                        ->name("{$panelId}.")
                        ->prefix($panel->getPath())
                        ->group(function () use ($panel): void {
                            /** @var Panel $panel */
                            if ($panel->hasPlugin(Lockscreen::get()->getId())) {
                                Route::post('lock-session', [LockscreenSessionController::class, 'lockSession'])
                                    ->name('lock-session');
                                Route::get(
                                    (filled(Lockscreen::get()->getUrl()) && Lockscreen::get()->getUrl() !== '/')
                                        ? Lockscreen::get()->getUrl()
                                        : '/screen/lock',
                                    LockerScreen::class
                                )->name('page');
                            }
                        });

                }

            }
        });
}
