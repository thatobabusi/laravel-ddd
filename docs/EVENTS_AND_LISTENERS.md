# Events & Listeners in DDD

Building event-driven, decoupled domains with Laravel-DDD.

## Event Design

### Domain Events
Events represent something that happened in the domain:

```bash
php artisan ddd:event Invoicing:InvoiceCreated
php artisan ddd:event Invoicing:InvoiceSent
php artisan ddd:event Invoicing:InvoicePaid
php artisan ddd:event Invoicing:InvoiceRefunded
```

### Event Structure
Events should be immutable and contain only domain-relevant data:

```php
namespace Domain\Invoicing\Events;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Invoice $invoice,
        public readonly string $createdBy = 'system',
    ) {}
}
```

### Avoid Anti-Patterns
```php
// ❌ BAD: Event does processing
class InvoiceCreated
{
    public function sendEmail() { ... }
    public function generatePDF() { ... }
}

// ✅ GOOD: Event just represents what happened
class InvoiceCreated
{
    public function __construct(public Invoice $invoice) {}
}
```

---

## Creating Listeners

### Generate Domain Listeners
```bash
php artisan ddd:listener Invoicing:HandleInvoiceCreatedNotification
php artisan ddd:listener Invoicing:HandleInvoiceCreatedLogging
php artisan ddd:listener Payment:HandleInvoiceCreatedPaymentReminder
```

### Listener Implementation
```php
namespace Domain\Invoicing\Listeners;

use Domain\Invoicing\Events\InvoiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleInvoiceCreatedNotification implements ShouldQueue
{
    public function handle(InvoiceCreated $event): void
    {
        Mail::to($event->invoice->customer_email)
            ->send(new InvoiceCreatedMail($event->invoice));
    }
}
```

### Using the #[ListensTo] Attribute
More modern, attribute-based listener registration:

```php
namespace Domain\Invoicing\Listeners;

use Domain\Invoicing\Events\InvoiceCreated;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Events\Dispatchable;

#[ListensTo(InvoiceCreated::class)]
class HandleInvoiceCreatedNotification
{
    public function __invoke(InvoiceCreated $event): void
    {
        // Auto-discovered when autoload.listeners is enabled
    }
}
```

Enable auto-discovery in config:
```php
'autoload' => [
    'listeners' => true, // default: false
],
```

---

## Dispatching Events

### In Actions
Dispatch events after domain operations complete:

```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Events\InvoiceCreated;

class CreateInvoice
{
    public function execute(array $data): Invoice
    {
        $invoice = Invoice::create($data);

        // Dispatch after transaction completes
        event(new InvoiceCreated($invoice));

        return $invoice;
    }
}
```

### In Models
Use model observers or boot methods:

```php
namespace Domain\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (self $invoice) {
            event(new InvoiceCreated($invoice));
        });

        static::updated(function (self $invoice) {
            event(new InvoiceUpdated($invoice));
        });
    }
}
```

Or use observers:
```bash
php artisan ddd:observer Invoicing:InvoiceObserver
```

```php
namespace Domain\Invoicing\Observers;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Events\InvoiceCreated;

class InvoiceObserver
{
    public function created(Invoice $invoice): void
    {
        event(new InvoiceCreated($invoice));
    }

    public function updated(Invoice $invoice): void
    {
        event(new InvoiceUpdated($invoice));
    }
}
```

Register in provider:
```php
Invoice::observe(InvoiceObserver::class);
```

---

## Async Event Processing

### Queue Listeners
For long-running operations, queue the listener:

```php
namespace Domain\Invoicing\Listeners;

use Domain\Invoicing\Events\InvoiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\WithoutRelations;

#[WithoutRelations]
class HandleInvoiceCreatedReport implements ShouldQueue
{
    public function __construct(private ReportGenerator $generator) {}

    public function handle(InvoiceCreated $event): void
    {
        // This runs in a queue job
        $this->generator->generateMonthlyReport($event->invoice);
    }
}
```

### Delayed Processing
```php
class HandleInvoiceCreatedReminder implements ShouldQueue
{
    public function handle(InvoiceCreated $event): void
    {
        // Process 7 days after invoice creation
        SendOverdueInvoiceReminder::dispatch($event->invoice)
            ->delay(now()->addDays(7));
    }
}
```

---

## Cross-Domain Communication

### Event Broadcasting
Allow multiple domains to listen to shared events:

```
Invoicing Domain:
  Events: InvoiceCreated, InvoicePaid
    ↓
  Shared Event Bus
    ↓
  Payment Domain (listens to InvoicePaid)
  Reporting Domain (listens to all)
  Email Domain (listens to InvoiceCreated)
```

### Implementation Pattern
```php
// Domain\Invoicing\Events\InvoiceCreated
namespace Domain\Invoicing\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InvoiceCreated
{
    use Dispatchable;
    public function __construct(public Invoice $invoice) {}
}

// Domain\Payment\Listeners\HandleInvoiceCreated
namespace Domain\Payment\Listeners;

#[ListensTo(InvoiceCreated::class)]
class HandleInvoiceCreatedPaymentSetup
{
    public function __invoke(InvoiceCreated $event): void
    {
        // Create payment record
    }
}

// Domain\Reporting\Listeners\HandleInvoiceCreated
namespace Domain\Reporting\Listeners;

#[ListensTo(InvoiceCreated::class)]
class HandleInvoiceCreatedReporting
{
    public function __invoke(InvoiceCreated $event): void
    {
        // Update reporting metrics
    }
}
```

---

## Event Subscribers

### Using Subscribers for Related Events
Group related event handlers together:

```bash
php artisan ddd:listener Invoicing:InvoiceEventSubscriber
```

```php
namespace Domain\Invoicing\Listeners;

use Domain\Invoicing\Events\InvoiceCreated;
use Domain\Invoicing\Events\InvoiceSent;
use Domain\Invoicing\Events\InvoicePaid;
use Illuminate\Events\Dispatcher;

class InvoiceEventSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            InvoiceCreated::class,
            [$this, 'handleCreated']
        );

        $events->listen(
            InvoiceSent::class,
            [$this, 'handleSent']
        );

        $events->listen(
            InvoicePaid::class,
            [$this, 'handlePaid']
        );
    }

    public function handleCreated(InvoiceCreated $event): void
    {
        // Log invoice creation
    }

    public function handleSent(InvoiceSent $event): void
    {
        // Update invoice status
    }

    public function handlePaid(InvoicePaid $event): void
    {
        // Trigger accounting sync
    }
}
```

Register in provider:
```php
Event::subscribe(InvoiceEventSubscriber::class);
```

---

## Testing Events

### Testing Event Dispatching
```php
namespace Tests\Domain\Invoicing\Actions;

use Domain\Invoicing\Actions\CreateInvoice;
use Domain\Invoicing\Events\InvoiceCreated;
use Illuminate\Support\Facades\Event;

class CreateInvoiceTest extends TestCase
{
    #[Test]
    public function it_dispatches_invoice_created_event()
    {
        Event::fake();

        $action = new CreateInvoice();
        $invoice = $action->execute([
            'customer_id' => 1,
            'amount' => 100,
        ]);

        Event::assertDispatched(InvoiceCreated::class, function ($event) use ($invoice) {
            return $event->invoice->id === $invoice->id;
        });
    }
}
```

### Testing Listeners
```php
namespace Tests\Domain\Invoicing\Listeners;

use Domain\Invoicing\Events\InvoiceCreated;
use Domain\Invoicing\Listeners\HandleInvoiceCreatedNotification;
use Domain\Invoicing\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class HandleInvoiceCreatedNotificationTest extends TestCase
{
    #[Test]
    public function it_sends_email_on_invoice_creation()
    {
        Mail::fake();

        $invoice = Invoice::factory()->create();
        $listener = new HandleInvoiceCreatedNotification();

        $listener->handle(new InvoiceCreated($invoice));

        Mail::assertSent(InvoiceCreatedMail::class);
    }
}
```

---

## Best Practices

### 1. Keep Events Pure
Events should represent facts, not commands:
```php
// ✅ GOOD: Event = something happened
class InvoicePaid { }

// ❌ BAD: Event = instruction to do something
class SendInvoiceEmail { }
```

### 2. Event Versioning
For long-lived events, consider versioning:
```php
namespace Domain\Invoicing\Events;

class InvoiceCreated
{
    public const VERSION = 2;

    public function __construct(
        public Invoice $invoice,
        public string $version = self::VERSION,
    ) {}
}
```

### 3. Fail-Safe Listeners
Ensure one failing listener doesn't break others:
```php
public function handle(InvoiceCreated $event): void
{
    try {
        // Send notification
    } catch (\Exception $e) {
        Log::error('Failed to send notification', ['error' => $e]);
        // Don't rethrow; other listeners must continue
    }
}
```

### 4. Idempotent Listeners
Listeners may run multiple times (retries); ensure idempotency:
```php
public function handle(InvoiceCreated $event): void
{
    // Use firstOrCreate instead of create
    InvoiceLog::firstOrCreate(
        ['invoice_id' => $event->invoice->id],
        ['status' => 'created']
    );
}
```

---

## Key Takeaways

1. **Events represent facts**, not instructions.
2. **Listeners should be single-responsibility**.
3. **Use #[ListensTo]** attribute for cleaner registration.
4. **Queue long-running listeners** for performance.
5. **Enable listener auto-discovery** in config.
6. **Test events and listeners separately**.
7. **Use subscribers** to group related listeners.
8. **Keep listeners idempotent** for safe retries.
