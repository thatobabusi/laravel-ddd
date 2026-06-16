# Performance & Optimization

Strategies for building high-performance Laravel-DDD applications.

## Production Autoloading

### Caching Domain Manifests
Always cache domain autoload metadata in production:

```bash
php artisan ddd:optimize
```

This caches:
- Domain provider manifests
- Command registrations
- Policy discovery mappings
- Factory resolutions
- Migration paths

Include in deployment process:
```bash
# deploy.php or CI/CD pipeline
php artisan ddd:optimize
php artisan optimize
```

### Clearing Cache
During development:
```bash
php artisan ddd:clear
```

---

## Query Optimization

### N+1 Query Prevention
Use eager loading in domain actions:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;

class FetchInvoiceForCustomer
{
    public function execute(int $customerId): Invoice
    {
        // ❌ Bad: causes N+1 queries if accessing customer data
        return Invoice::where('customer_id', $customerId)->first();

        // ✅ Good: eager loads relationships
        return Invoice::with(['customer', 'lineItems', 'payments'])
            ->where('customer_id', $customerId)
            ->first();
    }
}
```

### Database Indexing
Define indexes on frequently queried fields:

```php
// In migration
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->index();
    $table->string('invoice_number')->unique();
    $table->dateTime('created_at')->index();
    $table->dateTime('paid_at')->nullable()->index();
    $table->timestamps();
});
```

### Query Caching
Cache expensive queries:

```php
namespace Domain\Reporting\Actions;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Support\Facades\Cache;

class GetMonthlyRevenue
{
    public function execute(int $month, int $year): int
    {
        $cacheKey = "revenue-{$year}-{$month}";

        return Cache::remember($cacheKey, 3600, function () use ($month, $year) {
            return Invoice::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('paid_at', '!=', null)
                ->sum('amount_cents');
        });
    }
}
```

---

## Value Object Optimization

### Immutable Value Objects
Value objects should be immutable to reduce memory usage:

```php
namespace Domain\Invoicing\ValueObjects;

readonly class DollarAmount
{
    public function __construct(private int $cents) {}

    public function cents(): int
    {
        return $this->cents;
    }
}
```

### Value Object Caching
Cache frequently-created value objects:

```php
namespace Domain\Invoicing\ValueObjects;

class CurrencyCode
{
    private static array $cache = [];

    private function __construct(private readonly string $code) {}

    public static function from(string $code): self
    {
        return self::$cache[$code] ??= new self(strtoupper($code));
    }

    public function value(): string
    {
        return $this->code;
    }
}
```

---

## Database Connection Pooling

### Connection Pool Configuration
For high-concurrency applications:

```php
// config/database.php
'connections' => [
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'unix_socket' => env('DB_SOCKET'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
],
```

Consider using:
- **PgBouncer** (PostgreSQL)
- **ProxySQL** (MySQL)
- **Redis Cluster** (caching layer)

---

## Event & Job Queuing

### Dispatch Asynchronously
Use job queues for expensive operations:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Jobs\SendInvoiceEmailJob;

class CreateInvoice
{
    public function execute(array $data): Invoice
    {
        $invoice = Invoice::create($data);

        // Queue async instead of running synchronously
        dispatch(new SendInvoiceEmailJob($invoice))->onQueue('invoicing');

        return $invoice;
    }
}
```

### Job Configuration
Optimize queue workers:

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('QUEUE_NAME', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

Start workers with appropriate concurrency:
```bash
php artisan queue:work redis --queue=invoicing,default --tries=3 --timeout=90
```

---

## Caching Strategy

### Cache Layers
Implement multi-layer caching:

```php
namespace Domain\Invoicing\Actions;

use Illuminate\Support\Facades\Cache;

class GetInvoiceWithCache
{
    public function execute(int $invoiceId): Invoice
    {
        $cacheKey = "invoice-{$invoiceId}";

        return Cache::remember($cacheKey, 3600, function () use ($invoiceId) {
            // Hit database only on cache miss
            return Invoice::with('customer', 'lineItems')
                ->findOrFail($invoiceId);
        });
    }

    public function invalidateCache(int $invoiceId): void
    {
        Cache::forget("invoice-{$invoiceId}");
    }
}
```

### Cache Invalidation
Invalidate caches on domain updates:

```php
namespace Domain\Invoicing\Models;

class Invoice extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::updated(function (self $invoice) {
            Cache::forget("invoice-{$invoice->id}");
        });

        static::deleted(function (self $invoice) {
            Cache::forget("invoice-{$invoice->id}");
        });
    }
}
```

---

## Model Hydration Optimization

### Select Only Needed Columns
Reduce data transfer:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;

class ListInvoicesSummary
{
    public function execute(int $customerId): array
    {
        return Invoice::where('customer_id', $customerId)
            ->select(['id', 'invoice_number', 'amount_cents', 'created_at'])
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }
}
```

### Use Collections Instead of Large Arrays
For in-memory processing:

```php
use Illuminate\Support\Collection;

$invoices = Invoice::all(); // Collection (lazy)

$paid = $invoices->filter(fn($inv) => $inv->isPaid());
$total = $paid->sum(fn($inv) => $inv->amount->cents());
```

---

## Benchmark Patterns

### Timing Critical Operations
```php
namespace Domain\Invoicing\Actions;

use Illuminate\Support\Benchmarker;

class ProcessLargeInvoiceBatch
{
    public function execute(array $invoiceIds): void
    {
        Benchmarker::measure(['Creating invoices'], function () use ($invoiceIds) {
            foreach ($invoiceIds as $id) {
                // Process
            }
        });
    }
}
```

---

## Database Query Optimization

### Batch Inserts
```php
public function insertMany(array $records): void
{
    Invoice::query()->insert($records); // Single query
}
```

### Bulk Updates
```php
Invoice::whereIn('id', $ids)->update(['status' => 'paid']);
```

### Pagination
```php
Invoice::where('customer_id', $customerId)
    ->paginate(50); // Not all at once
```

---

## Monitoring & Profiling

### Query Debugging in Development
```php
// config/database.php
'logging' => env('DB_LOGGING', false),
'log_level' => env('DB_LOG_LEVEL', 'debug'),
```

Monitor with Laravel Telescope:
```bash
composer require --dev laravel/telescope
php artisan telescope:install
```

### Performance Monitoring
Track domain action performance:

```php
namespace Domain\Invoicing\Actions;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class CreateInvoice
{
    public function execute(array $data): Invoice
    {
        $start = Carbon::now();

        $invoice = Invoice::create($data);

        Log::info('Invoice created', [
            'duration_ms' => Carbon::now()->diffInMilliseconds($start),
            'invoice_id' => $invoice->id,
        ]);

        return $invoice;
    }
}
```

---

## Key Takeaways

1. **Always cache domain manifests** in production (`ddd:optimize`).
2. **Use eager loading** to prevent N+1 queries.
3. **Index frequently queried fields** in migrations.
4. **Dispatch expensive operations** to job queues asynchronously.
5. **Cache query results** with appropriate TTLs.
6. **Select only needed columns** to reduce data transfer.
7. **Batch operations** where possible (insert, update).
8. **Monitor query performance** with Telescope or equivalent.
9. **Use connection pooling** for high-concurrency applications.
10. **Profile before optimizing** — measure first.
