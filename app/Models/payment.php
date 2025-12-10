<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Household;
use MongoDB\BSON\Decimal128;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';

    protected $fillable = [
        'household_id',
        'amount',
        'payment_date',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public $timestamps = true;

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
}
