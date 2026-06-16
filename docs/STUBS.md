# Customizing Stubs

## Publishing Stubs
Interactive stub publisher:
```bash
php artisan ddd:stub
```

## Publishing Options
```bash
# All stubs
php artisan ddd:stub --all

# Specific stubs
php artisan ddd:stub model factory action

# With wildcards
php artisan ddd:stub listener.

# Overwrite only existing
php artisan ddd:stub model --existing

# Force overwrite
php artisan ddd:stub model --force
```

## Stub Priority
When generating objects, stubs are searched in order:
1. `stubs/ddd/*.stub` (DDD-specific)
2. `stubs/*.stub` (shared)
3. Package/framework defaults

## Listing Available Stubs
```bash
php artisan ddd:stub --list
```

Shows all available stubs and their sources (DDD vs framework).
