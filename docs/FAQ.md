# Frequently Asked Questions

## Installation & Setup

**Q: Do I need to run `ddd:install` every time?**
A: No. Run it once after installing the package. It publishes config and registers domain paths in `composer.json`.

**Q: Can I have multiple domains?**
A: Yes. Domains are independent namespaces. Create as many as needed via `ddd:*` commands.

## Configuration

**Q: Why isn't my custom layer showing up?**
A: You need to register the namespace in `composer.json`. Use `php artisan ddd:config composer` to auto-sync.

**Q: Can I change domain paths after installation?**
A: Yes, but ensure `composer.json` PSR-4 entries are updated to match. Use `ddd:config composer` to sync.

## Generation

**Q: What's the difference between `ddd:class` and `ddd:interface`?**
A: Classes are concrete implementations; interfaces define contracts. Both support custom namespaces via `/` prefix.

**Q: Can I nest objects deeper than one level?**
A: Yes, use forward slashes: `php artisan ddd:model Invoicing:Payment/Transaction/Item`.

**Q: How do I generate with options (factory, migration, etc)?**
A: Use flags: `php artisan ddd:model Invoicing:Invoice -mfs` (migration, factory, seeder).

## Autoloading

**Q: Why aren't my providers being discovered?**
A: Ensure `ddd.autoload.providers` is `true` and the class extends `ServiceProvider`.

**Q: Should I cache in production?**
A: Yes. Run `php artisan ddd:optimize` during deployment for faster autoloading.

**Q: How do I exclude certain folders from auto-discovery?**
A: Use `ddd.autoload_ignore` in config or register a custom filter callback.

## Performance

**Q: Does having many domains affect performance?**
A: No. Autoloading is cached in production via `ddd:optimize`.

**Q: When should I use custom resolvers?**
A: When you need non-standard naming conventions or paths for specific object types.

## Troubleshooting

**Q: "Class not found" after generation?**
A: Run `composer dump-autoload` to refresh PSR-4 mappings.

**Q: Migration files aren't being discovered?**
A: Ensure `ddd.autoload.migrations` is `true` and migrations are in the configured path.

**Q: How do I revert `ddd:upgrade`?**
A: Manually revert `composer.json` and delete generated domain paths. See UPGRADING.md.
