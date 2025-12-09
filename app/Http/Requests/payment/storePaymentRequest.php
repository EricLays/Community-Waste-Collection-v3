<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Create payment linked to household
     * amount: decimal string (we store Decimal128)
     */
    public function rules(): array
    {
        return [
            'household_id' => ['required', 'string'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'status'       => ['nullable', 'in:pending,paid,failed'],
            'payment_date' => ['nullable', 'date'],
        ];
    }
}
