<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Confirm payment
     * - optionally accept payment_date override
     */
    public function rules(): array
    {
        return [
            'payment_date' => ['nullable', 'date'],
        ];
    }
}
