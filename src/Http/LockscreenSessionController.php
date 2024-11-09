<?php

namespace lockscreen\FilamentLockscreen\Http;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class LockscreenSessionController
{
    /**
     * @throws \Exception
     */
    public function lockSession(): RedirectResponse
    {
        $currentPanel = filament()->getCurrentPanel();
        /**
         * Check if the request is still authenticated or not before rendering the page,
         * if not authenticated then redirect to the login page of current panel, or default panel if current panel could not be detected.
         */
        if (! Filament::auth()->check()) {
            if (filament()->getCurrentPanel()) {
                return redirect(filament()->getCurrentPanel()->getLoginUrl());
            }

            return redirect(filament()->getDefaultPanel()->getLoginUrl());
        }

        session(['lockscreen' => true]);

        return redirect()->route("lockscreen.{$currentPanel->getId()}.page");
    }
}
