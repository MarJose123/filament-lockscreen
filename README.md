![](https://github.com/MarJose123/filament-lockscreen/blob/2.x/art/filament-lockscreen-1x.png)

**_Give an ability to the user to lock their access without logging out of the system for a break._**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)
[![Total Downloads](https://img.shields.io/packagist/dt/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)

## Installation

You can install the package via Composer:

```console
composer require marjose123/filament-lockscreen
```

Panel provider configuration:
```php
use lockscreen\FilamentLockscreen\Lockscreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;
use lockscreen\FilamentLockscreen\Http\Middleware\LockerTimer;


public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            //.......
              Lockscreen::make()
                ->usingCustomTableColumns() // Use custom table columns. Default:  email, password.
                ->enableRateLimit() // Enable rate limit for the lockscreen. Default: Enable, 5 attempts in 1 minute.
                ->setUrl() // Customize the lockscreen url.
                ->enableIdleTimeout() // Enable auto lock during idle time. Default: Enable, 30 minutes.
               ->disableDisplayName() // Display the name of the user based on the attribute supplied. Default: name
               ->icon() // Customize the icon of the lockscreen.
               ->enablePlugin() // Enable the plugin.
        ]); 
}
```

## Testing

```bash
composer test
```

## Upgrade Guide

See [UPGRADE](docs/upgrade-from-v2-to-v3.md) from `2.x to 3.x`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

If you want to contribute to the Filament-lockerscreen package, you may want to test it in a real Laravel project:

* Fork this repository to your GitHub account.
* Create a Laravel app locally.
* Clone your fork in your Laravel app's root directory.
* In the /filament-lockscreen directory, create a branch for your fix, e.g. fix/error-message.

Install the packages in your app's `composer.json`:

```
{
   // ...
    "require": {
        "marjose123/filament-lockscreen": "*",
    },
    "repositories": [
        {
            "type": "path",
            "url": "filament-lockscreen"
        }
    ],
   // ...
}
```
Now, run `composer update`.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Marjose](https://github.com/MarJose123)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
