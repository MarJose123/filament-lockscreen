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
            if ($this->isLocked($request) && $this->isSegmentMatched($request)) {
                $panelId = filament()->getCurrentPanel()?->getId();

                return to_route("lockscreen.{$panelId}.page");
            }

            return $next($request);
        }

        /**
         *  Normal Lock session
         */
        if ($request->isMethod('GET') && $this->isLocked($request) && $this->isSegmentMatched($request)) {
            $panelId = filament()->getCurrentPanel()?->getId();

            redirect()->setIntendedUrl(url()->previous() ?? filament()->getDefaultPanel()->getPath());

            return to_route("lockscreen.{$panelId}.page");
        }
    }

    protected function isLocked(Request $request): bool
    {
        return (bool) $request->session()->get('lockscreen') === true;
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
