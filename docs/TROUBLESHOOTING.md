# Troubleshooting

## Common Issues

### "Class not found" after generation
**Solution:**
```bash
composer dump-autoload
```

The PSR-4 namespace mapping needs to be refreshed.

### Domain objects not being discovered (providers, commands, etc)
**Check:**
1. `ddd.autoload.*` settings in `config/ddd.php` are enabled.
2. Class extends the correct base class (e.g., `ServiceProvider`, `Command`).
3. File is not in `ddd.autoload_ignore` folders.
4. Run `php artisan ddd:clear && php artisan ddd:optimize`.

### Stubs not being used
**Check priority order:**
1. `stubs/ddd/{name}.stub` (customized DDD-only)
2. `stubs/{name}.stub` (shared)
3. Package defaults

Publish custom stubs:
```bash
php artisan ddd:stub --all
```

### Migrations not running
**Ensure:**
1. `ddd.autoload.migrations` is `true`.
2. Migration files are in configured `ddd.namespaces.migration` path.
3. Run `php artisan migrate`.

### Composer autoload conflicts
**Solution:**
```bash
php artisan ddd:config composer
composer dump-autoload
```

This syncs `composer.json` with `ddd.php` configuration.

### "Domain path not found" during generation
**Solution:**
Ensure the domain directory exists. Create it:
```bash
mkdir -p src/Domain/{YourDomain}
```

Or run `ddd:config detect` to auto-detect.

### Policy/Factory discovery not working
**Check:**
1. `ddd.autoload.policies` or `ddd.autoload.factories` is `true`.
2. Your app doesn't have custom `Gate::guessPolicyNamesUsing()` or `Factory::guessFactoryNamesUsing()`.
3. Run cache clear: `php artisan ddd:clear`.

### Performance issues in development
**Check:**
- Too many domains or deeply nested objects?
- Cache stale? Run `php artisan ddd:clear`.

In production, always run:
```bash
php artisan ddd:optimize
```

### Upgrade issues (v2 → v3)
**Run:**
```bash
php artisan ddd:upgrade
php artisan ddd:config update
composer dump-autoload
```

See [UPGRADING](../UPGRADING.md) for details.

## Getting Help

1. Check [FAQ](FAQ.md).
2. Search [GitHub Issues](https://github.com/thatobabusi/laravel-ddd/issues).
3. Review [UPGRADING](../UPGRADING.md) if upgrading versions.
4. Report issues with Laravel version, LaravelDDD version, and reproduction steps.
