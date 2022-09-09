<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;

class Locker
{
    public function handle($request, Closure $next)
    {
        if ($request->session()->get('lockscreen')) {
            return redirect()->route('lockscreenpage');
        }

        return $next($request);
    }

}
