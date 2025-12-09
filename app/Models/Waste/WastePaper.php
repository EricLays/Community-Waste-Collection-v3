<?php

namespace App\Models\Waste;

/**
 * Paper: normal rules
 * Cost: 50,000
 */
class WastePaper extends Waste
{
    protected $attributes = [
        'type' => 'paper',
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
