<?php

use Illuminate\Support\Facades\Route;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->prefix(config('filament.path'))
    ->group(function () {
        Route::get('/screen/lock', LockerScreen::class)->name('lockscreenpage')->middleware(['auth']);
    });
