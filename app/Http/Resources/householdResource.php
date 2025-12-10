<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Waste\Waste;

class Household extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'households';

    protected $fillable = [
        'owner_name',
        'address',
        'block',
        'no'
    ];

    public $timestamps = true;

    // Relasi ke Waste
    public function wastes()
    {
        return $this->hasMany(Waste::class);
    }

    // Relasi ke Payment
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}