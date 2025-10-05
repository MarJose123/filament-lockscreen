<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\LivewireManager;
use lockscreen\FilamentLockscreen\Lockscreen;

class Locker
{
    /**
     * @return RedirectResponse|mixed
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->isLivewireRequest()) {
            return $next($request);
        }

        /**
         *  Normal Lock session
         */
        if ($request->isMethod('GET') && $request->session()->get('lockscreen') && $this->isSegmentMatched($request)) {
            $panelId = filament()->getCurrentPanel()?->getId();

            redirect()->setIntendedUrl(url()->previous() ?? filament()->getDefaultPanel()->getPath());

            return to_route("lockscreen.{$panelId}.page");
        }

        /**
         *  Idle Lock session
         */
        $enableIdle = Lockscreen::get()->isEnableIdleTimeout();
        $idleTimeout = Lockscreen::get()->getIdleTimeout();

        if ($request->isMethod('GET') && $enableIdle) {
            $lastActivity = $request->session()->has('session_last_activity') ? $request->session()->get('session_last_activity') : null;

            if ($lastActivity && (time() - $lastActivity) > $idleTimeout) {
                $request->session()->put('lockscreen', true);
            }

            $request->session()->put('session_last_activity', time());
        }

        return $next($request);
    }

    protected function isSegmentMatched($request): bool
    {
        $panelPath = filament()->getCurrentPanel()?->getPath();
        $guardLockPath = Str::of($panelPath)->remove('/');
        // the panel is using the default path '/'
        if ($guardLockPath->isEmpty()) {
            return $request->is([$guardLockPath, $guardLockPath->append('*/*')]);
        }

        return $request->is([$guardLockPath, $guardLockPath->append('/*')]);
    }

    protected function isLivewireRequest(): bool
    {
        return class_exists(LivewireManager::class) && app(LivewireManager::class)->isLivewireRequest();
    }
}
