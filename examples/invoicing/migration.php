<?php

/**
 * Database Migration (database/migrations/YYYY_MM_DD_HHMMSS_create_invoices_table.php)
 *
 * Creates the invoices table for the Invoicing domain.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('amount_cents'); // Amount in cents
            $table->text('description');
            $table->dateTime('due_date');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            // Indexes for common queries
            $table->index('customer_id');
            $table->index('invoice_number');
            $table->index('created_at');
            $table->index('paid_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
