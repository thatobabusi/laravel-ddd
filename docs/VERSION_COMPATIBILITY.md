# Version Compatibility

## Laravel & PHP Version Matrix


| Laravel     | LaravelDDD | PHP  | Status                                                                                                                        |
|-------------|------------|------|-------------------------------------------------------------------------------------------------------------------------------|
| ^13.x       | 4.x        | 8.4+ | **Current** Experimental _(active WIP MVP)_                                                                                   |
| 11.x – 13.x | 3.x        | 8.3+ | **Latest** Maintained by [Jasper Tey](https://github.com/JasperTey) - [Laravel DDD](https://github.com/jaspertey/laravel-ddd) |
| 11.44.x+    | 2.x        | 8.3+ | Legacy Maintained by [Jasper Tey](https://github.com/JasperTey) - [Laravel DDD](https://github.com/jaspertey/laravel-ddd)     |
| 9.x – 11.x  | 0.x – 1.x  | 8.1+ | Legacy                                                                                                                        |

## Upgrading

### From v0 or v1 to v3
Requires Laravel 11.x or 12.x+.

**Steps:**
1. Update Laravel to 11.44.x+
2. Update `composer.json`: `composer require thatobabusi/laravel-ddd:^4.0`
3. Run upgrade: `php artisan ddd:upgrade`
4. Run config update: `php artisan ddd:config update`

### From v2 to v3
Same upgrade process. `ddd:upgrade` handles migration.

See [UPGRADING](../UPGRADING.md) for detailed migration guide.

## End of Life

v0, v1: EOL. Use v2 or v3.
