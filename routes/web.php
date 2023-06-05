<?php

use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;

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
