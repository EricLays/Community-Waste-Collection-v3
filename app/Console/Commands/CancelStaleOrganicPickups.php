<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelStaleOrganicPickups extends Command
{
    protected $signature = 'waste:cancel-organic-stale';
    protected $description = 'Auto-cancel organic pickups pending more than 3 days';

    public function handle(): int
    {
        // ... your logic here ...
        $this->info('Canceled stale organic pickups.');
        return self::SUCCESS;
    }
}
