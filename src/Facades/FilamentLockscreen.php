<?php

namespace lockscreen\FilamentLockscreen\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \lockscreen\FilamentLockscreen\FilamentLockscreen
 */
class FilamentLockscreen extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \lockscreen\FilamentLockscreen\FilamentLockscreen::class;
    }
}
