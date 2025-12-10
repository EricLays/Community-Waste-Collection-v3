<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Waste\Waste;

class CancelStaleOrganicPickups extends Command
{
    protected $signature = 'waste:cancel-organic-stale';
    protected $description = 'Auto-cancel organic pickups pending more than 3 days';

    public function handle(): int
    {
        $cutoff = now()->subDays(3);
        $stale = Waste::query()
            ->where('type', 'organic')
            ->where('status', 'pending')
            ->where('created_at', '<=', $cutoff)
            ->get();

        foreach ($stale as $w) { $w->cancel(); }
        $this->info("Canceled {$stale->count()} stale organic pickups.");
        return Command::SUCCESS;
    }
}
