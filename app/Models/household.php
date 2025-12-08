<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Household extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'households';

    protected $fillable = ['owner_name', 'address', 'block', 'no'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
