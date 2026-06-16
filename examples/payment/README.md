# Payment Domain — Subdomain Example

Demonstrates **subdomains** (nested domains) in Laravel-DDD.

## Structure

```
src/Domain/Payment/
├─ Enums/
│  └─ PaymentStatus.php           # Shared enum
├─ Internal/                       # Staff-facing subdomain
│  ├─ Models/
│  │  ├─ PaymentMethod.php
│  │  └─ RefundLog.php
│  ├─ Actions/
│  │  ├─ RecordPayment.php
│  │  └─ ProcessRefund.php
│  └─ ValueObjects/
│     └─ RefundReason.php
├─ Customer/                       # Customer-facing subdomain
│  ├─ Actions/
│  │  └─ SubmitPayment.php
│  ├─ ViewModels/
│  │  └─ PaymentHistoryViewModel.php
│  └─ Exceptions/
│     └─ InsufficientFundsException.php
└─ Events/                         # Shared events
   ├─ PaymentProcessed.php
   ├─ PaymentFailed.php
   └─ RefundProcessed.php
```

## Subdomain Concept

Subdomains separate concerns within a larger domain:
- **Internal:** Staff/admin operations (record payments, process refunds)
- **Customer:** Customer-facing operations (submit payment, view history)
- **Shared:** Common enums and events used across subdomains

## Generation

```bash
# Shared enum
php artisan ddd:enum Payment:PaymentStatus

# Internal subdomain (dot notation)
php artisan ddd:model Payment.Internal:PaymentMethod -m
php artisan ddd:model Payment.Internal:RefundLog -m
php artisan ddd:value Payment.Internal:RefundReason
php artisan ddd:action Payment.Internal:RecordPayment
php artisan ddd:action Payment.Internal:ProcessRefund

# Customer subdomain
php artisan ddd:action Payment.Customer:SubmitPayment
php artisan ddd:view-model Payment.Customer:PaymentHistoryViewModel
php artisan ddd:exception Payment.Customer:InsufficientFundsException

# Shared events
php artisan ddd:event Payment:PaymentProcessed
php artisan ddd:event Payment:PaymentFailed
php artisan ddd:event Payment:RefundProcessed
```

## Resulting Namespaces

```
Domain\Payment\Enums\PaymentStatus
Domain\Payment\Internal\Models\PaymentMethod
Domain\Payment\Internal\Actions\RecordPayment
Domain\Payment\Customer\Actions\SubmitPayment
Domain\Payment\Customer\Exceptions\InsufficientFundsException
Domain\Payment\Events\PaymentProcessed
```

## Cross-Subdomain Communication

The customer-facing `SubmitPayment` delegates to internal `RecordPayment`:

```php
// Customer subdomain
class SubmitPayment
{
    public function __construct(
        private RecordPayment $recordPayment  // Internal subdomain
    ) {}

    public function execute(...): void
    {
        // Validation (customer concern)
        if ($this->hasInsufficientFunds(...)) {
            throw new InsufficientFundsException();
        }

        // Delegate to internal (persistence concern)
        $this->recordPayment->execute(...);
    }
}
```

## Cross-Domain Events

The Payment domain listens to `InvoiceCreated` from the Invoicing domain:

```php
namespace Domain\Payment\Listeners;

#[ListensTo(\Domain\Invoicing\Events\InvoiceCreated::class)]
class HandleInvoiceCreatedPaymentSetup
{
    public function __invoke($event): void
    {
        // Create payment record for the new invoice
    }
}
```

## Key Takeaways

1. **Subdomains** organize complex domains into logical parts
2. **Dot notation** (`Payment.Internal`) creates subdomains
3. **Internal vs Customer** separates staff and user concerns
4. **Shared enums/events** live at the domain root
5. **Cross-subdomain delegation** keeps responsibilities clear
6. **Cross-domain events** decouple Payment from Invoicing

See [EXAMPLES.md](../../docs/EXAMPLES.md) for the full pattern.
