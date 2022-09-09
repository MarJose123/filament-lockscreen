<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
