<?php

namespace Domain\Invoicing\Events;

use Domain\Invoicing\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * InvoiceCreated Event
 *
 * Fired when a new invoice is created.
 * Other domains can listen to this event to perform side effects.
 */
class InvoiceCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Invoice $invoice
    ) {}
}
