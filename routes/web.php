<?php

use Illuminate\Support\Facades\Route;

Route::domain(config('filament.domain'))
    ->middleware(config('filament.middleware.base'))
    ->group(function () {
        Route::get('/screen/lock', [])->name('lockscreenpage')->middleware(['auth']);
    });
