<?php

namespace App\Models\Waste;

/**
 * Plastic: normal rules
 * Cost: 50,000
 */
class WastePlastic extends Waste
{
    protected $attributes = [
        'type' => 'plastic',
    ];

    public function canSchedule(): bool
    {
        return true;
    }

    public function cost(): int
    {
        return 50_000;
    }
}
