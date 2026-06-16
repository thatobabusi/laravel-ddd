<?php

/**
 * Invoicing Routes (routes/api.php)
 *
 * RESTful routes for invoice management.
 * Routed to application-layer controller.
 */

Route::prefix('invoices')->group(function () {
    // List invoices
    Route::get('/', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'index'])
        ->name('invoices.index');

    // Create invoice
    Route::post('/', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'store'])
        ->name('invoices.store');

    Route::prefix('{invoice}')->group(function () {
        // Show invoice
        Route::get('/', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'show'])
            ->name('invoices.show');

        // Send invoice
        Route::post('/send', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'send'])
            ->name('invoices.send');

        // Mark as paid
        Route::post('/pay', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'markAsPaid'])
            ->name('invoices.pay');

        // Delete invoice
        Route::delete('/', [App\Modules\Invoicing\Controllers\InvoiceController::class, 'destroy'])
            ->name('invoices.destroy');
    });
});
