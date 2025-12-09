<?php

namespace App\Http\Requests\Pickup;

use Illuminate\Foundation\Http\FormRequest;

class SchedulePickupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Schedule a pending pickup
     * - pickup_date: required (ISO date)
     * - safety_check: optional (boolean; used for electronic type)
     */
    public function rules(): array
    {
        return [
            'pickup_date' => ['required', 'date'],
            'safety_check' => ['nullable', 'boolean'],
        ];
    }
}
