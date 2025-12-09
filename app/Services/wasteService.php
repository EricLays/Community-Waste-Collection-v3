<?php

namespace App\Services;

use App\Models\Waste\Waste;
use App\Models\Waste\WasteFactory;
use App\Repositories\Contracts\WasteRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\HouseholdRepositoryInterface;
use Illuminate\Support\Carbon;

class WasteService
{
    public function __construct(
        private WasteRepositoryInterface      $wastes,
        private PaymentRepositoryInterface    $payments,
        private HouseholdRepositoryInterface  $households,
    ) {}

    /**
     * Create new pickup request (type required).
     * Business rule: block if household has unpaid payment.
     */
    public function createPickup(array $payload): Waste
    {
        $householdId = $payload['household_id'] ?? null;
        if (!$householdId) {
            throw new \InvalidArgumentException('household_id is required');
        }

        // Ensure household exists (optional but recommended)
        $this->households->findOrFail($householdId);

        // Rule #1: block if unpaid exists
        if ($this->payments->hasUnpaid($householdId)) {
            throw new \RuntimeException('Unpaid payment exists for this household');
        }

        // Polymorphic instantiation by type (organic/plastic/paper/electronic)
        $waste = WasteFactory::make($payload);  // sets status=pending by default
        return $this->wastes->create($waste);
    }

    /**
     * List pickups with filters: status, type, household_id; paginate.
     */
    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->wastes->paginate($filters, $perPage);
    }

    /**
     * Schedule pickup: only from pending; electronic requires safety_check=true.
     */
    public function schedule(string $id, \DateTimeInterface $date): Waste
    {
        $waste = $this->wastes->findOrFail($id);
        $waste->schedule($date);  // polymorphic guard inside model
        return $this->wastes->update($waste);
    }

    /**
     * Complete pickup: only scheduled; will auto-create a payment by type (50k or 100k).
     */
    public function complete(string $id): Waste
    {
        $waste = $this->wastes->findOrFail($id);

        // Perform completion on model (returns charge based on type)
        $amount = $waste->complete();

        // Auto-generate payment record
        $this->payments->create([
            'household_id' => $waste->household_id,
            'amount'       => $amount,
            'status'       => 'pending',
            'payment_date' => null,
        ]);

        return $this->wastes->update($waste);
    }

    /**
     * Cancel pickup: only pending/scheduled.
     */
    public function cancel(string $id): Waste
    {
        $waste = $this->wastes->findOrFail($id);
        $waste->cancel();
        return $this->wastes->update($waste);
    }

    /**
     * (Optional) Scheduled rule for Organic: auto-cancel after 3 days when not picked up.
     * Call from a command/cron.
     */
    public function autoCancelOrganicStale(): int
    {
        $threshold = Carbon::now()->subDays(3);
        $stale = $this->wastes->query()
            ->where('type', 'organic')
            ->whereIn('status', [Waste::STATUS_PENDING, Waste::STATUS_SCHEDULED])
            ->where('pickup_date', '<', $threshold)
            ->get();

        $count = 0;
        foreach ($stale as $w) {
            $w->cancel();
            $this->wastes->update($w);
            $count++;
        }
        return $count;
    }
}