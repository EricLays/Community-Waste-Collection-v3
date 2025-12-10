<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'household_id' => 'required|string',
            'amount'       => 'required|numeric|min:0',
        ];
    }
}
