# Autoloading & Discovery

## Configuration
Control auto-discovery in `config/ddd.php`:
```php
'autoload' => [
    'providers' => true,
    'commands' => true,
    'policies' => true,
    'factories' => true,
    'migrations' => true,
    'listeners' => false,  // opt-in
],
```

## What Gets Discovered

### Service Providers
Any class extending `ServiceProvider` in the domain layer is auto-registered.

### Console Commands
Any class extending `Command` in the domain layer is auto-registered.

### Policies
Auto-discovers policies for domain models and falls back to Laravel's default.

### Factories
Auto-discovers factories for domain models using PSR-4 class scanning.

### Migrations
Paths matching `ddd.namespaces.migration` are registered as migration paths.

### Event Listeners (Opt-in)
Enable to auto-discover listeners via `#[ListensTo]` attribute or `$listen` property.

## Ignoring Paths
Exclude folders from PSR-4 scanning:
```php
'autoload_ignore' => [
    'Tests',
    'Database/Migrations',
],
```

Custom filtering via callback:
```php
DDD::filterAutoloadPathsUsing(function (SplFileInfo $file) {
    return $file->getBasename() !== 'functions.php';
});
```

## Disabling Autoloading
Comment out the `autoload` config to disable entirely.

## Production Optimization
Cache domain manifests during deployment:
```bash
php artisan ddd:optimize
```

Clearing cache:
```bash
php artisan ddd:clear
```

Automatically included in `php artisan optimize`.
