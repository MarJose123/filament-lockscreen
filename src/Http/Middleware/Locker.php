<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Locker
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle($request, Closure $next)
    {
        /*
         * $request->method() === 'GET'
         * this will not block the request coming from Livewire page
         */
        if(config('filament-lockscreen.segment.specific_path_only', false))
        {
            $needle = config('filament-lockscreen.segment.segment_needle', 1);
            $blocked_path = config('filament-lockscreen.segment.locked_path');
            if ($request->session()->get('lockscreen') && $request->method() === 'GET' && in_array($request->segment($needle),$blocked_path))  return redirect()->route('lockscreenpage');
            return $next($request);

        }


        /*
         *  USE THIS CONDITION IF THE SEGMENT IS DISABLED IN THE CONFIG FILE
         *  All the request will be blocked
         */
        if ($request->session()->get('lockscreen') && $request->method() === 'GET') {
            return redirect()->route('lockscreenpage');
        }

        return $next($request);
    }

}
