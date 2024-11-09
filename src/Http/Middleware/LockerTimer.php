<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LockerTimer
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('lockscreen')) {
            $lastActivity = $request->session()->get('locker_last_activity');
            $activityTimeout = config('filament-lockscreen.activity_timeout', 60 * 30 /* 30 minutes */);

            if ($request->method() === 'GET' && $lastActivity && (time() - $lastActivity) > $activityTimeout) {
                $request->session()->put('lockscreen', true);
            }

            $request->session()->put('locker_last_activity', time());
        }

        return $next($request);
    }
}
