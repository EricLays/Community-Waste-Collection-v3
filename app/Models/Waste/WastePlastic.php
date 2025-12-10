<?php

namespace App\Models\Waste;

class WastePlastic extends Waste
{
    protected $attributes = [
        'type' => 'plastic',
        'status' => self::STATUS_PENDING
    ];
}
