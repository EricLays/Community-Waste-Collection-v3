<?php

namespace App\Http\Requests\Household;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHouseholdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Same fields as store, but typically all are optional on update
        return [
            'owner_name' => ['sometimes', 'string', 'max:200'],
            'address'    => ['sometimes', 'string', 'max:300'],
            'block'      => ['nullable', 'string', 'max:50'],
            'no'         => ['nullable', 'string', 'max:50'],
        ];
    }
}
