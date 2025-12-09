<?php

namespace App\Http\Requests\Household;

use Illuminate\Foundation\Http\FormRequest;

class StoreHouseholdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow; add auth logic if needed
    }

    public function rules(): array
    {
        return [
            'owner_name' => ['required', 'string', 'max:200'],
            'address'    => ['required', 'string', 'max:300'],
            'block'      => ['nullable', 'string', 'max:50'],
            'no'         => ['nullable', 'string', 'max:50'],
        ];
    }
}