<?php

namespace App\Models\Waste;

/**
 * Organic: normal rules; auto-cancel after 3 days (handled by scheduled command/job).
 * Cost: 50,000
 */
class WasteOrganic extends Waste
{
    protected $attributes = [
        'type' => 'organic',
    ];

    public function canSchedule(): bool
    {
        // No special constraints beyond being pending
        return true;
    }

    public function cost(): int
    {
        return 50_000;
    }
}
