<?php

namespace Domain\Invoicing\Actions;

use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\Events\InvoiceSent;
use Illuminate\Contracts\Mail\Mailer;

/**
 * SendInvoice Action
 *
 * Handles sending an invoice to the customer via email.
 * Updates invoice status after successful send.
 */
class SendInvoice
{
    public function __construct(
        private Mailer $mailer
    ) {}

    /**
     * Execute action
     */
    public function execute(Invoice $invoice, string $recipientEmail): void
    {
        if ($invoice->isPaid()) {
            throw new \DomainException('Cannot send a paid invoice.');
        }

        // Send email (would use Mail facade + Mailable in real app)
        // $this->mailer->to($recipientEmail)->send(new InvoiceMailable($invoice));

        // Update invoice status
        $invoice->markAsSent();

        // Dispatch event
        event(new InvoiceSent($invoice));
    }
}
