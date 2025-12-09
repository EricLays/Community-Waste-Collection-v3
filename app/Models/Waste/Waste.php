<?php

namespace App\Models\Waste;

use MongoDB\Laravel\Eloquent\Model;

class Waste extends Model    // ← not abstract
{
    protected $connection = 'mongodb';
    protected $collection = 'wastes';

    protected $fillable = [
        'household_id', 'type', 'pickup_date', 'status', 'safety_check', 'notes',
    ];

    protected $casts = [
        'pickup_date'  => 'datetime',
        'safety_check' => 'bool',
    ];

    public const STATUS_PENDING   = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED  = 'canceled';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * Optional: instantiate a type-specific subclass when hydrating
     * from the database (Single Table Inheritance).
     */

    #Access level to App\Models\Waste\Waste::newFromBuilder() must be public (as in class Illuminate\Database\Eloquent\Model)
    public function newFromBuilder($attributes = [], $connection = null) #error when protected
    {
        $attrs = (array) $attributes;
        $type  = $attrs['type'] ?? null;

        $map = [
            'organic'    => WasteOrganic::class,
            'plastic'    => WastePlastic::class,
            'paper'      => WastePaper::class,
            'electronic' => WasteElectronic::class,
        ];

        $class = $map[$type] ?? static::class;

        /** @var \App\Models\Waste\Waste $model */
        $model = new $class();
        $model->setConnection($connection ?: $this->getConnectionName());
        $model->exists = true;
        $model->setRawAttributes($attrs, true);
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    // Polymorphic behavior can stay in subclasses.
    // Base methods here are only placeholders if needed.
}
