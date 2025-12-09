<?php

namespace App\Models\Waste;

class WasteElectronic extends Waste
{
    protected $attributes = ['type' => 'electronic', 'safety_check' => false];

    public function schedule(\Illuminate\Support\Carbon $pickupDate): void
    {
        if (!$this->safety_check) {
            throw new \DomainException('Electronic waste requires safety_check=true before scheduling.');
        }
        parent::schedule($pickupDate);
    }

    public function complete(): int
    {
        parent::complete();
        return 100000;
    }
}
