<?php

namespace lockscreen\FilamentLockscreen\Http;

use Exception;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;

class LockscreenSessionController
{
    /**
     * @throws Exception
     */
    public function lockSession(): RedirectResponse
    {
        $currentPanel = filament()->getCurrentPanel();
        /**
         * Check if the request is still authenticated or not before rendering the page,
         * if not authenticated, then redirect to the login page of the current panel, or default panel if the current panel could not be detected.
         */
        if (! Filament::auth()->check()) {
            if (filament()->getCurrentPanel()) {
                return redirect()->setIntendedUrl(url()->previous() ?? filament()->getDefaultPanel()->getPath())
                    ->to(filament()->getCurrentPanel()->getLoginUrl());
            }

            return redirect()->setIntendedUrl(url()->previous() ?? filament()->getDefaultPanel()->getPath())
                ->to(filament()->getDefaultPanel()->getLoginUrl());
        }

        session()->put('lockscreen', true);
        redirect()->setIntendedUrl(url()->previous() ?? filament()->getDefaultPanel()->getPath());

        return to_route("lockscreen.{$currentPanel->getId()}.page");
    }
}
