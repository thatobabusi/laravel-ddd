<?php

namespace App\Modules\Invoicing\Controllers;

use App\Modules\Invoicing\Requests\StoreInvoiceRequest;
use Domain\Invoicing\Actions\CreateInvoice;
use Domain\Invoicing\Actions\SendInvoice;
use Domain\Invoicing\Models\Invoice;
use Domain\Invoicing\ValueObjects\DollarAmount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * InvoiceController
 *
 * Application layer controller that orchestrates between HTTP requests
 * and domain actions. Handles input/output but no business logic.
 */
class InvoiceController
{
    public function __construct(
        private CreateInvoice $createInvoice,
        private SendInvoice $sendInvoice,
    ) {}

    /**
     * GET /invoices
     * List all invoices
     */
    public function index(): JsonResponse
    {
        $invoices = Invoice::with('customer')
            ->orderByDesc('created_at')
            ->paginate(50);

        return response()->json($invoices);
    }

    /**
     * POST /invoices
     * Create a new invoice
     */
    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $invoice = $this->createInvoice->execute([
            'customer_id' => $request->integer('customer_id'),
            'invoice_number' => $request->string('invoice_number'),
            'amount' => DollarAmount::fromDollars(
                $request->float('amount')
            ),
            'description' => $request->string('description'),
            'due_date' => Carbon::parse($request->string('due_date')),
        ]);

        return response()->json($invoice, 201);
    }

    /**
     * GET /invoices/{id}
     * Show invoice details
     */
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice);
    }

    /**
     * POST /invoices/{id}/send
     * Send invoice to customer
     */
    public function send(Invoice $invoice, StoreInvoiceRequest $request): JsonResponse
    {
        try {
            $this->sendInvoice->execute(
                $invoice,
                $request->string('customer_email')
            );

            return response()->json([
                'message' => 'Invoice sent successfully',
                'invoice' => $invoice,
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * POST /invoices/{id}/pay
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice): JsonResponse
    {
        if ($invoice->isPaid()) {
            return response()->json([
                'error' => 'Invoice is already paid',
            ], 422);
        }

        $invoice->markAsPaid();

        return response()->json($invoice);
    }

    /**
     * DELETE /invoices/{id}
     * Delete invoice (draft only)
     */
    public function destroy(Invoice $invoice): JsonResponse
    {
        if (!$invoice->canBeDeleted()) {
            return response()->json([
                'error' => 'Cannot delete a sent or paid invoice',
            ], 422);
        }

        $invoice->delete();

        return response()->json(null, 204);
    }
}
