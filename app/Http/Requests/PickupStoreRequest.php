<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PickupStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'household_id' => ['required', 'string'],
            'type'         => ['required', 'in:organic,plastic,paper,electronic'],
            'safety_check' => ['sometimes', 'boolean'], // only enforced before scheduling
            'pickup_date'  => ['nullable', 'date'],
            'status'       => ['nullable', 'in:pending,scheduled,completed,canceled'],
        ];
    }
}
