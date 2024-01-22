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
        if ($request->method() === 'GET' && $request->session()->get('lockscreen') ) {
            $panelId = filament()->getCurrentPanel()?->getId();
            return redirect()->route("lockscreen.{$panelId}.page");
        }
        return $next($request);
    }
}
