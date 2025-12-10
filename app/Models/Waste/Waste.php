<?php

namespace App\Models\Waste;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Household;

class Waste extends Model
{
    protected $collection = 'wastes';
    protected $connection = 'mongodb';

    protected $fillable = [
        'household_id',
        'pickup_date',
        'status',
        'type',
        'safety_check'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
    ];

    // default timestamps
    public $timestamps = true;

    // Relationship
    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    // Enum helper for status
    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';
}
