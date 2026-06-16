# Real-World Examples

Complete working examples for building domain-driven Laravel applications.

## Example 1: Invoicing Domain

A multi-faceted invoicing system with models, actions, value objects, and DTOs.

### Directory Structure
```
src/Domain/Invoicing/
├─ Models/
│  └─ Invoice.php
├─ ValueObjects/
│  ├─ DollarAmount.php
│  ├─ InvoiceNumber.php
│  └─ InvoicePeriod.php
├─ Data/
│  └─ CreateInvoicePayload.php
├─ Actions/
│  ├─ CreateInvoice.php
│  ├─ SendInvoiceToCustomer.php
│  └─ MarkInvoiceAsPaid.php
├─ Events/
│  ├─ InvoiceCreated.php
│  ├─ InvoiceSent.php
│  └─ InvoicePaid.php
├─ Exceptions/
│  ├─ InvoiceNotFoundException.php
│  └─ InvalidInvoiceAmountException.php
└─ Policies/
   └─ InvoicePolicy.php
```

### Generate the Structure
```bash
php artisan ddd:model Invoicing:Invoice -m
php artisan ddd:value Invoicing:DollarAmount
php artisan ddd:value Invoicing:InvoiceNumber
php artisan ddd:value Invoicing:InvoicePeriod
php artisan ddd:dto Invoicing:CreateInvoicePayload
php artisan ddd:action Invoicing:CreateInvoice
php artisan ddd:action Invoicing:SendInvoiceToCustomer
php artisan ddd:action Invoicing:MarkInvoiceAsPaid
php artisan ddd:event Invoicing:InvoiceCreated
php artisan ddd:event Invoicing:InvoiceSent
php artisan ddd:event Invoicing:InvoicePaid
php artisan ddd:exception Invoicing:InvoiceNotFoundException
php artisan ddd:exception Invoicing:InvalidInvoiceAmountException
php artisan ddd:policy Invoicing:InvoicePolicy
```

### Value Object: DollarAmount
```php
namespace Domain\Invoicing\ValueObjects;

class DollarAmount
{
    public function __construct(
        private readonly int $cents
    ) {
        if ($cents < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative.');
        }
    }

    public static function fromDollars(int|float $dollars): self
    {
        return new self((int) ($dollars * 100));
    }

    public function toDollars(): float
    {
        return $this->cents / 100;
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function equals(self $other): bool
    {
        return $this->cents === $other->cents;
    }
}
```

### Model: Invoice
```php
namespace Domain\Invoicing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Domain\Invoicing\ValueObjects\InvoiceNumber;

class Invoice extends Model
{
    use HasFactory;

    protected $casts = [
        'amount_cents' => 'integer',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function getAmountAttribute(): DollarAmount
    {
        return new DollarAmount($this->amount_cents);
    }

    public function getNumberAttribute(): InvoiceNumber
    {
        return InvoiceNumber::from($this->invoice_number);
    }

    public function markAsPaid(): void
    {
        $this->update(['paid_at' => now()]);
    }

    public function isSent(): bool
    {
        return $this->sent_at !== null;
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }
}
```

### Action: CreateInvoice
```php
namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Data\CreateInvoicePayload;
use Domain\Invoicing\Events\InvoiceCreated;

class CreateInvoice
{
    public function execute(CreateInvoicePayload $payload): Invoice
    {
        $invoice = Invoice::create([
            'customer_id' => $payload->customer_id,
            'invoice_number' => $payload->invoice_number,
            'amount_cents' => $payload->amount->cents(),
            'description' => $payload->description,
            'due_date' => $payload->due_date,
        ]);

        event(new InvoiceCreated($invoice));

        return $invoice;
    }
}
```

### DTO: CreateInvoicePayload
```php
namespace Domain\Invoicing\Data;

use Spatie\LaravelData\Data;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Carbon\CarbonImmutable;

class CreateInvoicePayload extends Data
{
    public function __construct(
        public int $customer_id,
        public string $invoice_number,
        public DollarAmount $amount,
        public string $description,
        public CarbonImmutable $due_date,
    ) {}
}
```

### Event: InvoiceCreated
```php
namespace Domain\Invoicing\Events;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Invoice $invoice) {}
}
```

---

## Example 2: Payment Domain with Subdomains

A hierarchical payment system with internal (staff) and customer-facing concerns.

### Directory Structure
```
src/Domain/Payment/
├─ Internal/
│  ├─ Models/
│  │  ├─ PaymentMethod.php
│  │  └─ RefundLog.php
│  ├─ Actions/
│  │  ├─ RecordPayment.php
│  │  └─ ProcessRefund.php
│  └─ ValueObjects/
│     └─ RefundReason.php
├─ Customer/
│  ├─ ViewModels/
│  │  └─ PaymentHistoryViewModel.php
│  ├─ Actions/
│  │  └─ SubmitPayment.php
│  └─ Exceptions/
│     └─ InsufficientFundsException.php
└─ Shared/
   ├─ Events/
   │  ├─ PaymentProcessed.php
   │  ├─ PaymentFailed.php
   │  └─ RefundProcessed.php
   └─ Enums/
      └─ PaymentStatus.php
```

### Generate Subdomains
```bash
# Internal subdomain
php artisan ddd:model Payment.Internal:PaymentMethod -m
php artisan ddd:model Payment.Internal:RefundLog -m
php artisan ddd:value Payment.Internal:RefundReason
php artisan ddd:action Payment.Internal:RecordPayment
php artisan ddd:action Payment.Internal:ProcessRefund

# Customer subdomain
php artisan ddd:view-model Payment.Customer:PaymentHistoryViewModel
php artisan ddd:action Payment.Customer:SubmitPayment
php artisan ddd:exception Payment.Customer:InsufficientFundsException

# Shared enums/events
php artisan ddd:enum Payment:PaymentStatus
php artisan ddd:event Payment:PaymentProcessed
php artisan ddd:event Payment:PaymentFailed
php artisan ddd:event Payment:RefundProcessed
```

### Enum: PaymentStatus
```php
namespace Domain\Payment\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';

    public function isTerminal(): bool
    {
        return in_array($this, [self::Completed, self::Failed, self::Refunded]);
    }
}
```

### Action: SubmitPayment (Customer)
```php
namespace Domain\Payment\Customer\Actions;

use Domain\Payment\Customer\Exceptions\InsufficientFundsException;
use Domain\Payment\Internal\Actions\RecordPayment;
use Domain\Payment\Enums\PaymentStatus;

class SubmitPayment
{
    public function __construct(
        private RecordPayment $recordPayment
    ) {}

    public function execute(
        int $customer_id,
        int $amount_cents,
        string $payment_method
    ): void {
        if ($this->hasInsufficientFunds($customer_id, $amount_cents)) {
            throw new InsufficientFundsException();
        }

        $this->recordPayment->execute(
            customer_id: $customer_id,
            amount_cents: $amount_cents,
            payment_method: $payment_method,
            status: PaymentStatus::Processing,
        );
    }

    private function hasInsufficientFunds(int $customerId, int $amount): bool
    {
        // Check business logic
        return false;
    }
}
```

---

## Example 3: Testing Domain Objects

### Testing a Model
```php
namespace Tests\Domain\Invoicing\Models;

use PHPUnit\Framework\TestCase;
use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\ValueObjects\DollarAmount;

class InvoiceTest extends TestCase
{
    #[Test]
    public function it_can_be_marked_as_paid()
    {
        $invoice = Invoice::factory()->create(['paid_at' => null]);
        
        $invoice->markAsPaid();
        
        $this->assertTrue($invoice->isPaid());
        $this->assertNotNull($invoice->paid_at);
    }

    #[Test]
    public function it_calculates_amount_as_value_object()
    {
        $invoice = Invoice::factory()->create(['amount_cents' => 10000]);
        
        $this->assertInstanceOf(DollarAmount::class, $invoice->amount);
        $this->assertEquals(100.0, $invoice->amount->toDollars());
    }
}
```

### Testing an Action
```php
namespace Tests\Domain\Invoicing\Actions;

use PHPUnit\Framework\TestCase;
use Domain\Invoicing\Actions\CreateInvoice;
use Domain\Invoicing\Data\CreateInvoicePayload;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Event;

class CreateInvoiceTest extends TestCase
{
    #[Test]
    public function it_creates_invoice_and_fires_event()
    {
        Event::fake();
        
        $action = new CreateInvoice();
        $payload = new CreateInvoicePayload(
            customer_id: 1,
            invoice_number: 'INV-001',
            amount: DollarAmount::fromDollars(100),
            description: 'Services',
            due_date: CarbonImmutable::now()->addDays(30),
        );

        $invoice = $action->execute($payload);

        $this->assertNotNull($invoice->id);
        $this->assertEquals(1, $invoice->customer_id);
        Event::assertDispatched('InvoiceCreated');
    }
}
```

### Testing a Value Object
```php
namespace Tests\Domain\Invoicing\ValueObjects;

use PHPUnit\Framework\TestCase;
use Domain\Invoicing\ValueObjects\DollarAmount;

class DollarAmountTest extends TestCase
{
    #[Test]
    public function it_converts_from_dollars()
    {
        $amount = DollarAmount::fromDollars(99.99);
        
        $this->assertEquals(9999, $amount->cents());
        $this->assertEquals(99.99, $amount->toDollars());
    }

    #[Test]
    public function it_rejects_negative_amounts()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        new DollarAmount(-100);
    }

    #[Test]
    public function it_compares_amounts()
    {
        $amount1 = DollarAmount::fromDollars(50);
        $amount2 = DollarAmount::fromDollars(50);
        $amount3 = DollarAmount::fromDollars(100);

        $this->assertTrue($amount1->equals($amount2));
        $this->assertFalse($amount1->equals($amount3));
    }
}
```

---

## Usage in Controllers (Application Layer)

```php
namespace App\Modules\Invoicing\Controllers;

use Domain\Invoicing\Actions\CreateInvoice;
use Domain\Invoicing\Data\CreateInvoicePayload;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Illuminate\Http\Request;

class InvoiceController
{
    public function __construct(
        private CreateInvoice $createInvoice
    ) {}

    public function store(Request $request)
    {
        $payload = new CreateInvoicePayload(
            customer_id: $request->input('customer_id'),
            invoice_number: $request->input('invoice_number'),
            amount: DollarAmount::fromDollars($request->input('amount')),
            description: $request->input('description'),
            due_date: $request->input('due_date'),
        );

        $invoice = $this->createInvoice->execute($payload);

        return response()->json($invoice);
    }
}
```

---

## Key Takeaways

1. **Separate domains** into logical namespaces (Invoicing, Payment, etc).
2. **Use value objects** to encapsulate business logic and validation.
3. **Leverage DTOs** for data transfer between layers.
4. **Actions** contain business operations and dispatch events.
5. **Events** decouple domains and enable reactive workflows.
6. **Test at the domain level**, not just in controllers.
7. **Subdomains** organize related concerns within a larger domain.
