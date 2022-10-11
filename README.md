**_Give an ability to the user to lock their access without logging out of the system for a break._**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/marjose123/filament-lockscreen/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/marjose123/filament-lockscreen/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)

![LockScreen](/art/Filament%20Lock%20screen.png "LockScreen")


## Installation

You can install the package via composer:

```bash
composer require marjose123/filament-lockscreen
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-lockscreen-views"
```
Add this middleware `Locker::class` to your `Filament Config` and you're ready to go
```php
// use lockscreen\FilamentLockscreen\Http\Middleware\Locker;

    'middleware' => [
        'auth' => [
          //....
            Locker::class,
        ],
       // .....
    ],
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
