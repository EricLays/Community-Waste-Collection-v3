<?php

namespace App\Models\Waste;

class WastePaper extends Waste
{
    protected $attributes = [
        'type' => 'paper',
        'status' => self::STATUS_PENDING
    ];
}
