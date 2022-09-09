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
         *
         */
        if ($request->session()->get('lockscreen') && $request->method() === 'GET') {
            return redirect()->route('lockscreenpage');
        }

        return $next($request);
    }

}
