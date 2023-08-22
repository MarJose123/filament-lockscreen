![](https://github.com/MarJose123/filament-lockscreen/blob/2.x/art/filament-lockscreen-1x.png)

**_Give an ability to the user to lock their access without logging out of the system for a break._**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)
[![Total Downloads](https://img.shields.io/packagist/dt/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)

---
:rotating_light: _For latest version that support FilamentPhp v2.x use this branch [1.x](https://github.com/MarJose123/filament-lockscreen/tree/1.x)_

## Installation

You can install the package via composer:

```bash
composer require marjose123/filament-lockscreen:"^2.0"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-lockscreen-views"
```
Add the plugin to your panel and add the middleware  `Locker::class` to your panel  and you're ready to go
```php 
use lockscreen\FilamentLockscreen\Lockscreen;
use lockscreen\FilamentLockscreen\Http\Middleware\Locker;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(new Lockscreen());  // <- Add this
         ->authMiddleware([
                // ...
                 Locker::class, // <- Add this
            ]);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

If you want to contribute to Filament-lockerscreen packages, you may want to test it in a real Laravel project:

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

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
