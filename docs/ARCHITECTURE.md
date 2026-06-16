# Advanced Architecture

## Layer Customization
Objects like Controllers, Requests, and Middleware can be routed to an `Application` layer instead of the `Domain` layer.

```php
'application_objects' => [
    'controller',
    'request',
    'middleware',
],
```

## Custom Object Resolvers
Register a resolver in `AppServiceProvider` for custom naming/path conventions:

```php
DDD::resolveObjectSchemaUsing(function (...) {
    // Custom logic
});
```

## Autoloading Discovery
The package automatically discovers:
- Service Providers
- Console Commands
- Policies
- Factories
- Migrations
- Event Listeners (opt-in)
