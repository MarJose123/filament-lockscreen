<?php

namespace lockscreen\FilamentLockscreen;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Filament\PluginServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Livewire\Livewire;
use lockscreen\FilamentLockscreen\Http\Livewire\LockerScreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use Spatie\LaravelPackageTools\Package;

class FilamentLockscreenServiceProvider extends PluginServiceProvider
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
            ->hasRoute('web')
            ;
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
                    ->label('Lock Screen')
                    ->url(route('lockscreenpage'))
                    ->icon('heroicon-s-lock-closed'),
            ]);
        });

        Livewire::component('LockerScreen', LockerScreen::class);
    }
}
