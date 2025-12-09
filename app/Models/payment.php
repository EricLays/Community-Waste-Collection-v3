<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // official package
use MongoDB\BSON\Decimal128;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';

    protected $fillable = ['household_id', 'amount', 'payment_date', 'status'];

    protected $casts = [
        'household_id' => 'object_id',
        'payment_date' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = new Decimal128((string)$value);
    }

    public function getAmountAttribute($value)
    {
        return (string)$value; // parse as string to avoid float issues in clients
    }
}
