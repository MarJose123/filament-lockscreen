<?php

namespace lockscreen\FilamentLockscreen\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\LivewireManager;

class Locker
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->isLivewireRequest()) {
            return $next($request);
        }

        if ($request->isMethod('GET') && $request->session()->get('lockscreen') && $this->isSegmentMatched($request)) {
            $panelId = filament()->getCurrentPanel()?->getId();

            return redirect()->route("lockscreen.{$panelId}.page");
        }

        return $next($request);
    }

    /**
     * @param $request
     * @return boolean
     */
    protected function isSegmentMatched($request): bool
    {
        $panelPath = filament()->getCurrentPanel()?->getPath();
        $guardLockPath = Str::of($panelPath)->remove('/');

        return $request->is([$guardLockPath, $guardLockPath->append('/*')]);
    }

    protected function isLivewireRequest(): bool
    {
        return class_exists(LivewireManager::class) && app(LivewireManager::class)->isLivewireRequest();
    }
}
