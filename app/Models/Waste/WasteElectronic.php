<?php
namespace App\Models\Waste;

class WasteElectronic extends Waste
{
    // Ensure 'type' is automatically set to "electronic"
    protected $attributes = [
        'type'          => 'electronic',
        'safety_check'  => true,   // default on create
    ];

    // If your framework version requires, you can also explicitly include
    // safety_check in fillable here (inherits from base Waste):
    // protected $fillable = [
    //     'household_id', 'type', 'status', 'pickup_date', 'safety_check',
    // ];

    public function canSchedule(): bool
    {
        // Electronic must pass safety check before scheduling
        return $this->status === 'pending' && ($this->safety_check === true);
    }

    public function schedule(\DateTimeInterface $date): void
    {
        if (!$this->canSchedule()) {
            throw new \DomainException(
                'Electronic waste cannot be scheduled until safety_check is true.'
            );
        }

        $this->pickup_date = $date;
        $this->status      = 'scheduled';
        $this->save();
    }
}
