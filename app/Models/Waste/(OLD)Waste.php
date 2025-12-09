<?php

namespace App\Models\Waste;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

/**
 * Base Waste model stored in one "wastes" collection (MongoDB).
 * Polymorphic behavior is implemented by subclasses via canSchedule(), cost(), etc.
 */
abstract class Waste extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'wastes';

    /**
     * Common fillables across all waste types.
     * - type: discriminator (organic|plastic|paper|electronic)
     * - status: pending|scheduled|completed|canceled
     * - safety_check: only relevant for electronic type (bool)
     */
    protected $fillable = [
        'household_id',
        'type',
        'pickup_date',
        'status',
        'safety_check',
        // optional metadata fields:
        'notes',
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'safety_check' => 'bool',
    ];

    // ---- Status constants (consistency across domain) ----
    public const STATUS_PENDING   = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELED  = 'canceled';

    // ---- Default attributes / sensible defaults ----
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    // ---- Polymorphic contracts implemented by subclasses ----
    abstract public function canSchedule(): bool;
    abstract public function cost(): int;

    // ---- Guarded state transitions implemented in the base ----
    public function schedule(\DateTimeInterface $date): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new \RuntimeException('Pickup can only be scheduled from pending status');
        }
        if (!$this->canSchedule()) {
            throw new \RuntimeException('Pickup cannot be scheduled due to type-specific constraints');
        }

        $this->pickup_date = Carbon::parse($date);
        $this->status = self::STATUS_SCHEDULED;
        $this->save();
    }

    /**
     * Complete the pickup and return the charge amount for Payment creation.
     */
    public function complete(): int
    {
        if ($this->status !== self::STATUS_SCHEDULED) {
            throw new \RuntimeException('Only scheduled pickups can be completed');
        }

        $this->status = self::STATUS_COMPLETED;
        $this->save();

        return $this->cost();
    }

    public function cancel(): void
    {
        if (!in_array($this->status, [self::STATUS_PENDING, self::STATUS_SCHEDULED], true)) {
            throw new \RuntimeException('Only pending or scheduled pickups can be canceled');
        }
        $this->status = self::STATUS_CANCELED;
        $this->save();
    }

    // ---- Helpful query scopes (used by reports/jobs) ----
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForHousehold($query, string $householdId)
    {
        return $query->where('household_id', $householdId);
    }
}
