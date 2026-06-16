<?php

namespace App\Modules\Invoicing\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreInvoiceRequest
 *
 * Form request for creating/updating invoices.
 * Validates input before it reaches domain layer.
 */
class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_number' => 'required|string|unique:invoices|max:50',
            'amount' => 'required|numeric|min:0.01|max:999999.99',
            'description' => 'required|string|max:1000',
            'due_date' => 'required|date|after:today',
            'customer_email' => 'email|required_when:send,true',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'invoice_number.unique' => 'This invoice number is already in use.',
            'amount.min' => 'Invoice amount must be at least $0.01.',
            'due_date.after' => 'Due date must be in the future.',
        ];
    }
}
