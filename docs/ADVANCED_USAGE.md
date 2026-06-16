# Advanced Usage Patterns

## Nested Objects
Objects can be nested using forward slashes:
```bash
php artisan ddd:model Invoicing:Payment/Transaction
# → Domain\Invoicing\Models\Payment\Transaction

php artisan ddd:action Invoicing:Payment/ProcessTransaction
# → Domain\Invoicing\Actions\Payment\ProcessTransaction
```

## Subdomains
Use dot notation for nested domains:
```bash
php artisan ddd:view-model Reporting.Internal:MonthlyInvoicesReportViewModel
# → Domain\Reporting\Internal\ViewModels\MonthlyInvoicesReportViewModel
```

## Runtime Namespace Overrides
Use leading `/` to override configured namespace:
```bash
php artisan ddd:provider Invoicing:/InvoiceServiceProvider
# → Domain\Invoicing\InvoiceServiceProvider (not configured Providers folder)

php artisan ddd:exception Invoicing:/Models/Exceptions/InvoiceNotFoundException
# → Domain\Invoicing\Models\Exceptions\InvoiceNotFoundException
```

## Custom Object Resolution
Register a custom resolver for advanced naming conventions:

```php
// In AppServiceProvider::boot()
use Tey\LaravelDDD\Facades\DDD;
use Tey\LaravelDDD\ValueObjects\CommandContext;
use Tey\LaravelDDD\ValueObjects\ObjectSchema;

DDD::resolveObjectSchemaUsing(function (
    string $domainName,
    string $nameInput,
    string $type,
    CommandContext $command
): ?ObjectSchema {
    if ($type === 'controller' && $command->option('api')) {
        return new ObjectSchema(
            name: str($nameInput)->replaceEnd('Controller', '')->finish('ApiController')->toString(),
            namespace: "App\\Api\\Controllers\\{$domainName}",
            fullyQualifiedName: "App\\Api\\Controllers\\{$domainName}\\{$name}",
            path: "src/App/Api/Controllers/{$domainName}/{$name}.php",
        );
    }
    return null; // Fall back to defaults
});
```

Result:
```bash
php artisan ddd:controller Invoicing:PaymentController --api
# → src/App/Api/Controllers/Invoicing/PaymentApiController.php
```

## Application Layer Customization
Route certain objects outside the domain:
```php
'application_path' => 'app/Modules',
'application_namespace' => 'App\Modules',
'application_objects' => [
    'controller',
    'request',
    'middleware',
],
```

Generation result:
```bash
php artisan ddd:model Invoicing:Invoice --controller --resource --requests
```
Output structure:
```
├─ app/Modules/Invoicing
│   ├─ Controllers/InvoiceController.php
│   └─ Requests/{StoreInvoiceRequest,UpdateInvoiceRequest}.php
└─ src/Domain/Invoicing/Models/Invoice.php
```

## Custom Layers
Define additional top-level namespaces:
```php
'layers' => [
    'Infrastructure' => 'src/Infrastructure',
    'Integrations' => 'src/Integrations',
],
```

Usage:
```bash
php artisan ddd:model Infrastructure:Cache/CacheKeyBuilder
# → Infrastructure\Cache\CacheKeyBuilder
```

Register namespaces in `composer.json` automatically:
```bash
php artisan ddd:config composer
```
