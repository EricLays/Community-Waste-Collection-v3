<?php

namespace App\Models\Waste;

class WasteElectronic extends Waste
{
    protected $attributes = [
        'type' => 'electronic',
        'status' => self::STATUS_PENDING,
        'safety_check' => false
    ];

    public function canBeScheduled()
    {
        return $this->safety_check === true;
    }
}
