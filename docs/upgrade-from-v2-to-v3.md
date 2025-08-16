# Updating Dependencies
- `php` to `^8.2` or higher
- `danharrin/livewire-rate-limiting` to `^2.1.0`
- `filament/filament` to `^4.0`


# FilamentPHP Lockscreen Plugin: v2.x to v3.x Upgrade Guide

This guide will help you migrate your FilamentPHP Lockscreen plugin from v2.x to v3.x. The main change in v3.x is that configuration has been moved from a separate config file to fluent method calls on the plugin class.

## Prerequisites

- Ensure you're running a compatible version of FilamentPHP
- Back up your existing configuration before starting the migration

## Migration Steps

### 1. Remove the Config File

The `config/lockscreen.php` file is no longer used in v3.x. You can safely delete this file after completing the migration.

### 2. Update Plugin Registration

Replace your existing plugin registration with the new fluent configuration methods. Here's how to migrate each configuration option:

#### Basic Plugin Setup

```php
// Add this to your Panel configuration
Lockscreen::make()
    ->enablePlugin()
```

#### Migrating Icon Configuration

If you were using a custom icon in v2.x:

```php
// v2.x config file
'icon' => 'heroicon-s-lock-closed',
```

Update to v3.x:

```php
Lockscreen::make()
    ->icon('heroicon-s-lock-closed')
    ->enablePlugin()
```

#### Migrating URL Configuration

If you were using a custom URL in v2.x:

```php
// v2.x config file
'url' => '/screen/lock',
```

Update to v3.x:

```php
Lockscreen::make()
    ->setUrl('/screen/lock')
    ->enablePlugin()
```

#### Migrating Table Columns Configuration

If you were using custom table columns in v2.x:

```php
// v2.x config file
'table_columns' => [
    'account_username_field' => 'email',
    'account_password_field' => 'password',
],
```

Update to v3.x:

```php
Lockscreen::make()
    ->usingCustomTableColumns('email', 'password')
    ->enablePlugin()
```

**Note:** If you were using the default values (`email` and `password`), you can omit this method call as these are the defaults.

#### Migrating Rate Limit Configuration

If you had rate limiting enabled in v2.x:

```php
// v2.x config file
'rate_limit' => [
    'enable_rate_limit' => true,
    'rate_limit_max_count' => 5,
    'force_logout' => false,
],
```

Update to v3.x:

```php
Lockscreen::make()
    ->enableRateLimit(5) // Pass max attempts as parameter
    ->enablePlugin()
```

**Note:** Rate limiting is enabled by default with 5 attempts. If you want to disable it, simply omit the `enableRateLimit()` method.

#### Migrating Activity Timeout Configuration

If you were using a custom activity timeout in v2.x:

```php
// v2.x config file
'activity_timeout' => 60 * 30, // 30 minutes
```

Update to v3.x:

```php
Lockscreen::make()
    ->enableIdleTimeout(1800) // 30 minutes in seconds
    ->enablePlugin()
```

**Note:** The timeout value should be provided in seconds. The default is 30 minutes (1800 seconds).

### 3. Complete Migration Example

Here's a complete example showing how a fully configured v2.x setup would look in v3.x:

#### v2.x Configuration (config/lockscreen.php)

```php
<?php
return [
    'icon' => 'heroicon-s-shield-check',
    'url' => '/admin/lock',
    'table_columns' => [
        'account_username_field' => 'username',
        'account_password_field' => 'password',
    ],
    'rate_limit' => [
        'enable_rate_limit' => true,
        'rate_limit_max_count' => 3,
        'force_logout' => false,
    ],
    'activity_timeout' => 60 * 15, // 15 minutes
];
```

#### v3.x Configuration

```php
Lockscreen::make()
    ->icon('heroicon-s-shield-check')
    ->setUrl('/admin/lock')
    ->usingCustomTableColumns('username', 'password')
    ->enableRateLimit(3)
    ->enableIdleTimeout(900) // 15 minutes in seconds
    ->enablePlugin()
```

### 4. Additional v3.x Features

v3.x introduces some new configuration options that weren't available in v2.x:

#### Disable Display Name

```php
Lockscreen::make()
    ->disableDisplayName()
    ->enablePlugin()
```

This disables showing the user's name on the lockscreen.

## Breaking Changes Summary

1. **Config file removal**: The `config/lockscreen.php` file is no longer used
2. **Method chaining**: All configuration is now done through fluent method calls
3. **Parameter changes**: Some methods now accept parameters directly instead of reading from config
4. **Timeout units**: Activity timeout is now specified in seconds instead of being calculated

## Troubleshooting

### Common Issues

1. **Plugin not working**: Ensure you're calling `->enablePlugin()` at the end of your method chain
2. **Custom table columns not recognized**: Make sure you're passing both username and password field names to `usingCustomTableColumns()`
3. **Rate limiting not working**: Verify you're calling `enableRateLimit()` with the correct maximum attempts parameter

### Validation

After migration, test the following functionality:

- [ ] Lockscreen appears after idle timeout
- [ ] Custom icon displays correctly (if configured)
- [ ] Rate limiting works with failed login attempts
- [ ] Custom URL redirects properly (if configured)
- [ ] Authentication works with custom table columns (if configured)

## Support

If you encounter issues during migration, please:

1. Check that all method calls are properly chained
2. Verify parameter types and values
3. Ensure you're using compatible versions of FilamentPHP and the plugin

Remember to delete your old `config/lockscreen.php` file once you've confirmed the migration is working correctly.
