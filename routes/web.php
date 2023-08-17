<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;


Route::name('lockscreen')
    ->group(function (){
        foreach (Filament::getPanels() as $panel) {
            $panelId = $panel->getId();
            $hasTenancy = $panel->hasTenancy();
            $tenantRoutePrefix = $panel->getTenantRoutePrefix();
            $tenantSlugAttribute = $panel->getTenantSlugAttribute();
            $domains = $panel->getDomains();

            foreach ((empty($domains) ? [null] : $domains) as $domain) {
                Route::domain($domain)
                    ->middleware($panel->getMiddleware())
                    ->prefix($panel->getPath())
                    ->group(function () {
                        Route::get(
                            (config()->has('filament-lockscreen.url') && config('filament-lockscreen.url') != '' && config('filament-lockscreen.url') != '/')
                                ? config('filament-lockscreen.url')
                                : '/screen/lock',
                            LockerScreen::class
                        )->name('lockscreenpage')->middleware(['auth']);
                    });

            }


        }
    });

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->prefix(config('filament.path'))
    ->group(function () {
        Route::get(
            (config()->has('filament-lockscreen.url') && config('filament-lockscreen.url') != '' && config('filament-lockscreen.url') != '/')
                ? config('filament-lockscreen.url')
                : '/screen/lock',
            LockerScreen::class
        )->name('lockscreenpage')->middleware(['auth']);
    });
