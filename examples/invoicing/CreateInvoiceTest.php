<?php

namespace Tests\Domain\Invoicing\Actions;

use PHPUnit\Framework\TestCase;
use Domain\Invoicing\Actions\CreateInvoice;
use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Domain\Invoicing\Events\InvoiceCreated;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;

/**
 * CreateInvoice Action Tests
 *
 * Validates action business logic, event dispatch, and error handling.
 */
class CreateInvoiceTest extends TestCase
{
    use DatabaseTransactions;

    private CreateInvoice $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = app(CreateInvoice::class);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_creates_invoice_with_valid_data(): void
    {
        $invoice = $this->action->execute([
            'customer_id' => 1,
            'invoice_number' => 'INV-001',
            'amount' => DollarAmount::fromDollars(100),
            'description' => 'Services rendered',
            'due_date' => CarbonImmutable::now()->addDays(30),
        ]);

        $this->assertNotNull($invoice->id);
        $this->assertEquals(1, $invoice->customer_id);
        $this->assertEquals('INV-001', $invoice->invoice_number);
        $this->assertEquals(10000, $invoice->amount_cents);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_dispatches_invoice_created_event(): void
    {
        Event::fake();

        $invoice = $this->action->execute([
            'customer_id' => 1,
            'invoice_number' => 'INV-002',
            'amount' => DollarAmount::fromDollars(50),
            'description' => 'Test',
            'due_date' => CarbonImmutable::now()->addDays(30),
        ]);

        Event::assertDispatched(InvoiceCreated::class, function ($event) use ($invoice) {
            return $event->invoice->id === $invoice->id;
        });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_zero_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->action->execute([
            'customer_id' => 1,
            'invoice_number' => 'INV-003',
            'amount' => DollarAmount::fromDollars(0),
            'description' => 'Test',
            'due_date' => CarbonImmutable::now()->addDays(30),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_duplicate_invoice_number(): void
    {
        $this->expectException(\DomainException::class);

        Invoice::factory()->create(['invoice_number' => 'INV-DUP']);

        $this->action->execute([
            'customer_id' => 1,
            'invoice_number' => 'INV-DUP',
            'amount' => DollarAmount::fromDollars(100),
            'description' => 'Test',
            'due_date' => CarbonImmutable::now()->addDays(30),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_missing_customer_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->action->execute([
            'invoice_number' => 'INV-004',
            'amount' => DollarAmount::fromDollars(100),
            'description' => 'Test',
            'due_date' => CarbonImmutable::now()->addDays(30),
        ]);
    }
}
