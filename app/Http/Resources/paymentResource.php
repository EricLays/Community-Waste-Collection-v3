<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => (string) $this->_id,
            'household_id' => (string) $this->household_id,
            'amount'       => (string) $this->amount, // Decimal128 exposed as string
            'status'       => $this->status,
            'payment_date' => optional($this->payment_date)->toIso8601String(),
            'created_at'   => optional($this->created_at)->toIso8601String(),
        ];
    }
}
