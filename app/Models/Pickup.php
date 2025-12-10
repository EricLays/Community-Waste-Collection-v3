<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pickup extends Model
{
    protected $collection = 'wastes';
    protected $connection = 'mongodb';

    // Allow mass assignment for fields you create on POST:
    protected $fillable = [
        'household_id',
        'type',          // organic|plastic|paper|electronic
        'status',        // pending|scheduled|completed|canceled
        'pickup_date',   // nullable datetime
        //'safety_check'
        // ...any other fields
    ];
    public $timestamps = true;
    
}
