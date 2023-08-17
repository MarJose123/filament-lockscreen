<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;

class FilamentLockscreenServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-lockscreen')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web');
    }

    /**
     * @throws BindingResolutionException
     */
    public function bootingPackage()
    {
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', Locker::class);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'lockscreen' => UserMenuItem::make()
                    ->label(__('filament-lockscreen::default.user_menu_title'))
                    ->url(route('lockscreenpage'))
                    ->icon(config('filament-lockscreen.icon')),
            ]);
        });

        Livewire::component('LockerScreen', LockerScreen::class);
    }
}
