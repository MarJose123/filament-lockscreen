<?php

namespace filament-lockscreen\FilamentLockscreen;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use filament-lockscreen\FilamentLockscreen\Commands\FilamentLockscreenCommand;

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
            ->hasMigration('create_filament-lockscreen_table')
            ->hasCommand(FilamentLockscreenCommand::class);
    }
}
