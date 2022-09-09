<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->prefix(config('filament.path'))
    ->group(function () {
        Route::get('/screen/lock', [])->name('lockscreenpage')->middleware(['auth']);
    });
