<?php

namespace App\Models\Waste;

class WasteOrganic extends Waste
{
    protected $attributes = [
        'type' => 'organic',
        'status' => self::STATUS_PENDING
    ];

    // rule: auto cancel if > 3 days
    public function isExpired()
    {
        if (!$this->pickup_date) return false;
        return now()->diffInDays($this->pickup_date) > 3;
    }
}
