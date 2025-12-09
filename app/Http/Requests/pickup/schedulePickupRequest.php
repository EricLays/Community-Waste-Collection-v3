<?php

namespace App\Http\Requests\Pickup;  // ← match folder

use Illuminate\Foundation\Http\FormRequest;

class StorePickupRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'household_id' => ['required','string'], // add regex if you want ObjectId check
            'type'         => ['required','string','in:organic,plastic,paper,electronic'],
            'safety_check' => ['nullable','boolean','required_if:type,electronic'],
            'notes'        => ['nullable','string','max:500'],
        ];
    }
}
