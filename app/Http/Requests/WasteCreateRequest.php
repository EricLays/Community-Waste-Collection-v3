<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WasteCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'household_id' => 'required|string|exists:households,_id',
            'type' => 'required|in:organic,plastic,paper,electronic',
            'safety_check' => 'required_if:type,electronic|boolean',
        ];
    }
}
