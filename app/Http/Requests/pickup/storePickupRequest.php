<?php

namespace App\Http\Requests\Pickup;

use Illuminate\Foundation\Http\FormRequest;

class StorePickupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Create waste pickup request
     * - household_id: required (string ObjectId)
     * - type: required (one of organic, plastic, paper, electronic)
     * - safety_check: optional (boolean; only relevant for electronic)
     */
    public function rules(): array
    {
        return [
            'household_id' => ['required', 'string'],
            'type'         => ['required', 'in:organic,plastic,paper,electronic'],
            'safety_check' => ['nullable', 'boolean'],
        ];
    }
}
