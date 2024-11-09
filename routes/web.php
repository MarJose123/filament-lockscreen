<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\LockscreenSessionController;

Route::name('lockscreen.')
    ->group(function () {
        foreach (Filament::getPanels() as $panel) {
            $panelId = $panel->getId();
            $domains = $panel->getDomains();

            foreach ((empty($domains) ? [null] : $domains) as $domain) {
                Route::domain($domain)
                    ->middleware($panel->getMiddleware())
                    ->name("{$panelId}.")
                    ->prefix($panel->getPath())
                    ->group(function () {
                        Route::post('lock-session', [LockscreenSessionController::class, 'lockSession'])
                            ->name('lock-session');
                        Route::get(
                            (config()->has('filament-lockscreen.url') && config('filament-lockscreen.url') !== '' && config('filament-lockscreen.url') !== '/')
                                ? config('filament-lockscreen.url')
                                : '/screen/lock',
                            LockerScreen::class
                        )->name('page');
                    });

            }

        }
    });
