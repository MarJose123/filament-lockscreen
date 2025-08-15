<?php

namespace lockscreen\FilamentLockscreen;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasViews()
            ->hasTranslations()
            ->hasRoute('web');
    }

    /**
     * @throws BindingResolutionException
     */
    public function bootingPackage(): void
    {
        parent::packageBooted();
        //        $router = $this->app->make(Router::class);
        //        $router->pushMiddlewareToGroup('web', Locker::class);
    }
}
