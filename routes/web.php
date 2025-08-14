<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\LockscreenSessionController;
use lockscreen\FilamentLockscreen\Lockscreen;

Route::name('lockscreen.')
    ->group(function (): void {
        foreach (Filament::getPanels() as $panel) {
            $panelId = $panel->getId();
            $domains = $panel->getDomains();

            foreach ((blank($domains) ? [null] : $domains) as $domain) {
                Route::domain($domain)
                    ->middleware($panel->getMiddleware())
                    ->name("{$panelId}.")
                    ->prefix($panel->getPath())
                    ->group(function (): void {
                        Route::post('lock-session', [LockscreenSessionController::class, 'lockSession'])
                            ->name('lock-session');
                        Route::get(
                            (filled(Lockscreen::get()->getUrl()) && Lockscreen::get()->getUrl() !== '/')
                                ? Lockscreen::get()->getUrl()
                                : '/screen/lock',
                            LockerScreen::class
                        )->name('page');
                    });

            }

        }
    });
