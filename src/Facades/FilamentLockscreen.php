<?php

namespace filament-lockscreen\FilamentLockscreen\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \filament-lockscreen\FilamentLockscreen\FilamentLockscreen
 */
class FilamentLockscreen extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \filament-lockscreen\FilamentLockscreen\FilamentLockscreen::class;
    }
}
