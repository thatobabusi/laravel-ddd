# Configuration Guide

## Config Wizard
Interactive setup for `ddd.php`:
```bash
php artisan ddd:config wizard
```

## Manual Config
The `ddd.php` file controls:
- **Domain Layer:** `domain_path` (default: `src/Domain`), `domain_namespace` (default: `Domain`).
- **Application Layer:** `application_path` (default: `app/Modules`), `application_namespace` (default: `App\Modules`).
- **Custom Layers:** Define additional namespaces like `Infrastructure`.

## Syncing composer.json
After changing layer paths, sync your `composer.json` automatically:
```bash
php artisan ddd:config composer
```

## Config Utility Commands
```bash
php artisan ddd:config update  # Merge with latest package defaults
php artisan ddd:config detect  # Detect namespaces from composer.json
```
