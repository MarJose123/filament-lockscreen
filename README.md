![](https://github.com/MarJose123/filament-lockscreen/blob/2.x/art/filament-lockscreen-1x.png)

**_Give an ability to the user to lock their access without logging out of the system for a break._**

[![Latest Version on Packagist](https://img.shields.io/packagist/v/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)
[![Total Downloads](https://img.shields.io/packagist/dt/marjose123/filament-lockscreen.svg?style=flat-square)](https://packagist.org/packages/marjose123/filament-lockscreen)

:construction: This branch is under development and not yet ready for Filament v3 :construction:

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
use lockscreen\Lockscreen\Lockscreen;
use lockscreen\Lockscreen\Http\Middleware\Locker;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(new Lockscreen::make());  // <- Add this
         ->authMiddleware([
                // ...
                 Locker::class, // <- Add this
            ]);
}
```
<details>
<summary>Config file: </summary>

```php
return [

    /*
     *  Lock Screen Icon
     */
    'icon' => 'heroicon-s-lock-closed',

    /*
     *  Lock Screen URL
     * 
     * Note: do not provide base url `/` or empty, otherwise it will return default url
     */
    'url' => '/screen/lock',

    /*
    | ------------------------------------------------------------------------------------------------
    |   Table Column Name
    | ------------------------------------------------------------------------------------------------
    | Change the table column name if your login authentication column is checking on a different field and not on the default field ('email and password') column of the table.
    */
    'table_columns' => [
        'account_username_field' => 'email',
        'account_password_field' => 'password',
    ],

    /* =======================================
     *   if `enable_redirect_to` is TRUE then after login, it will be redirected to the route setup under `redirect_route`
     */
    'enable_redirect_to' => false,
    'redirect_route' => 'filament.pages.dashboard',

    /* =======================================
    *   RATE LIMIT
     *  change to false the `enable_rate_limit` to disable preventing user to input after several login failure.
    */
    'rate_limit' => [
        'enable_rate_limit' => true,
        'rate_limit_max_count' => 5, // max count for failure login allowed.
        'force_logout' => false,
    ],
    /* =========================
    *  Path segmentation locking
    *  e.g., if the segment is enabled then locked_path = ['admin', 'employee']
    *  www.domain.com/admin/ <== Locked, because this segment path is added to the locked_path
    *  www.domain.com/employee/ <== Locked, because this segment path is added to the locked_path
    *  www.domain.com/portal/ <== unlocked and will not be checked by the locker middleware even if the user lock their screen
    *
    * Note: make sure your segment_needle and allowed path is aligned
    */
    'segment' => [
        'specific_path_only' => false, // if false, then all the request will be blocked by the locker and will be redirected to the authentication page
        'segment_needle' => 1, // see the https://laravel.com/api/9.x/Illuminate/Http/Request.html#method_segment
        'locked_path' => [], //
    ],
];
```
</details>

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
