# Invoicing Domain — Complete Working Example

A production-ready example of a complete DDD-structured invoicing system.

## Structure

```
examples/invoicing/
├─ DollarAmount.php                 # Value object (immutable, type-safe)
├─ Invoice.php                       # Domain model
├─ CreateInvoiceAction.php           # Business logic (create)
├─ SendInvoiceAction.php             # Business logic (send)
├─ InvoiceCreatedEvent.php           # Domain event
├─ InvoiceSentEvent.php              # Domain event
├─ InvoiceController.php             # Application layer (HTTP)
├─ StoreInvoiceRequest.php           # Form request validation
├─ DollarAmountTest.php              # Value object tests
├─ CreateInvoiceTest.php             # Action tests
├─ migration.php                     # Database migration
├─ routes.php                        # API routes
└─ README.md                         # This file
```

## Key Concepts

### 1. Value Objects (DollarAmount.php)
- **Immutable:** Cannot be modified after creation
- **Type-safe:** Encapsulates monetary logic
- **Business logic:** Validation, arithmetic, formatting

```php
$amount = DollarAmount::fromDollars(99.99);
echo $amount->formatted('USD'); // $99.99
```

### 2. Domain Model (Invoice.php)
- **State:** Represents invoice data
- **Methods:** Business operations (isSent, markAsPaid, etc.)
- **Casts:** Value objects auto-converted from database

```php
$invoice->markAsPaid();
echo $invoice->isPaid() ? 'Paid' : 'Pending';
```

### 3. Actions (CreateInvoiceAction.php, SendInvoiceAction.php)
- **Encapsulation:** All business logic in dedicated classes
- **Transactions:** Database integrity with `DB::transaction()`
- **Events:** Dispatch after operation completes

```php
$invoice = $action->execute([...]);
// Event fired automatically
```

### 4. Domain Events (InvoiceCreatedEvent.php, InvoiceSentEvent.php)
- **Facts:** Represent what happened, not instructions
- **Subscribers:** Other domains can listen and react
- **Async:** Queued for asynchronous processing

```php
event(new InvoiceCreated($invoice));
// Triggers listeners in other domains
```

### 5. Application Layer (InvoiceController.php)
- **HTTP only:** Routes and HTTP concerns
- **Delegation:** Calls domain actions
- **No logic:** Business logic stays in domain

```php
public function store(StoreInvoiceRequest $request)
{
    $invoice = $this->createInvoice->execute([...]);
    return response()->json($invoice);
}
```

### 6. Form Requests (StoreInvoiceRequest.php)
- **Input validation:** Before domain
- **Authorization:** Who can perform this action
- **Feedback:** User-friendly error messages

---

## Usage

### Generate This Structure

```bash
# From the package root:
php artisan ddd:model Invoicing:Invoice -m
php artisan ddd:value Invoicing:DollarAmount
php artisan ddd:action Invoicing:CreateInvoice
php artisan ddd:action Invoicing:SendInvoice
php artisan ddd:event Invoicing:InvoiceCreated
php artisan ddd:event Invoicing:InvoiceSent
php artisan ddd:controller Invoicing:InvoiceController --resource
php artisan ddd:request Invoicing:StoreInvoiceRequest
```

Then copy the code from these example files into your generated files.

### Test the Domain

```bash
# Run tests
composer test tests/Domain/Invoicing/ValueObjects/DollarAmountTest.php
composer test tests/Domain/Invoicing/Actions/CreateInvoiceTest.php
```

### Create an Invoice via API

```bash
curl -X POST http://localhost:8000/api/invoices \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "invoice_number": "INV-2024-001",
    "amount": 99.99,
    "description": "Professional services",
    "due_date": "2024-07-15"
  }'
```

Response:
```json
{
  "id": 1,
  "customer_id": 1,
  "invoice_number": "INV-2024-001",
  "amount_cents": 9999,
  "description": "Professional services",
  "due_date": "2024-07-15T00:00:00Z",
  "sent_at": null,
  "paid_at": null,
  "created_at": "2024-06-16T12:00:00Z",
  "updated_at": "2024-06-16T12:00:00Z"
}
```

### Send Invoice to Customer

```bash
curl -X POST http://localhost:8000/api/invoices/1/send \
  -H "Content-Type: application/json" \
  -d '{
    "customer_email": "customer@example.com"
  }'
```

### Mark Invoice as Paid

```bash
curl -X POST http://localhost:8000/api/invoices/1/pay
```

---

## Events & Listeners

When an invoice is created, `InvoiceCreated` event fires. Other domains can listen:

```php
// In Domain\Payment\Listeners\HandleInvoiceCreatedPaymentSetup
#[ListensTo(InvoiceCreated::class)]
class HandleInvoiceCreatedPaymentSetup
{
    public function __invoke(InvoiceCreated $event): void
    {
        // Create payment record in Payment domain
    }
}
```

Enable auto-discovery in `config/ddd.php`:
```php
'autoload' => [
    'listeners' => true,
],
```

---

## Testing

### Test Value Objects

```php
public function test_dollar_amount_arithmetic()
{
    $amount1 = DollarAmount::fromDollars(50);
    $amount2 = DollarAmount::fromDollars(30);
    
    $sum = $amount1->add($amount2);
    $this->assertEquals(8000, $sum->cents());
}
```

### Test Actions

```php
public function test_create_invoice()
{
    $invoice = $this->action->execute([
        'customer_id' => 1,
        'invoice_number' => 'INV-001',
        'amount' => DollarAmount::fromDollars(100),
        'description' => 'Services',
        'due_date' => now()->addDays(30),
    ]);
    
    $this->assertNotNull($invoice->id);
}
```

---

## Key Takeaways

1. **Separate concerns:** Domain, application, and HTTP layers
2. **Value objects:** Encapsulate validation and logic
3. **Actions:** Single responsibility for business operations
4. **Events:** Decouple domains via pub/sub
5. **Tests:** Test domain, not HTTP
6. **Immutability:** Value objects don't change
7. **Transactions:** Ensure data integrity
8. **Delegation:** Controllers delegate to actions

---

## Next Steps

- Copy these examples into your project
- Modify for your business domain
- Add more actions (update, delete, etc.)
- Implement listeners in other domains
- Write comprehensive tests
- Deploy confidently

See [EXAMPLES.md](../../docs/EXAMPLES.md) in docs for more details.
