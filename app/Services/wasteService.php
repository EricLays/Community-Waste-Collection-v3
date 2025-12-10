<?php

namespace App\Services;

use App\Models\Waste\Waste;
use App\Models\Waste\WasteElectronic;
use App\Models\Waste\WasteOrganic;
use App\Models\Waste\WastePlastic;
use App\Models\Waste\WastePaper;
use InvalidArgumentException;

final class WasteService
{
    /**
     * Factory: build the correct Waste subclass based on `type`.
     * Sets safe defaults for creation (status=pending, safety_check for electronic).
     */
    public function makeWaste(array $data): Waste
    {
        $type = $data['type'] ?? null;
        if (!$type) {
            throw new InvalidArgumentException('Missing waste type.');
        }

        // Ensure default status on create
        $data['status'] = $data['status'] ?? 'pending';

        // Map type -> subclass
        return match ($type) {
            'electronic' => new WasteElectronic([
                'household_id' => $data['household_id'] ?? null,
                'type'         => 'electronic',
                'status'       => $data['status'],
                'pickup_date'  => $data['pickup_date'] ?? null,
                // default false unless provided
                'safety_check' => (bool)($data['safety_check'] ?? false),
            ]),

            'organic' => new WasteOrganic([
                'household_id' => $data['household_id'] ?? null,
                'type'         => 'organic',
                'status'       => $data['status'],
                'pickup_date'  => $data['pickup_date'] ?? null,
            ]),

            'plastic' => new WastePlastic([
                'household_id' => $data['household_id'] ?? null,
                'type'         => 'plastic',
                'status'       => $data['status'],
                'pickup_date'  => $data['pickup_date'] ?? null,
            ]),

            'paper' => new WastePaper([
                'household_id' => $data['household_id'] ?? null,
                'type'         => 'paper',
                'status'       => $data['status'],
                'pickup_date'  => $data['pickup_date'] ?? null,
            ]),

            default => throw new InvalidArgumentException("Unknown waste type: {$type}"),
        };
    }

    
    public function householdCanCreate(string $householdId): bool
    {
        // 1) Must exist
        if (!$this->householdRepository->exists($householdId)) {
            return false;
        }
        
 //   2) Must NOT have any unpaid payments
        // Consider "pending" and "failed" as "unpaid". If you only want "pending", adjust below.
        $hasUnpaid = $this->paymentRepository->hasUnpaidByHousehold($householdId, statuses: ['pending', 'failed']);

        if ($hasUnpaid) {
            return false;
        }

        // 3) Optional – ask repository for additional domain/data-layer checks
        // (e.g., preventing duplicate open requests, etc.)
        return $this->wasteRepository->householdCanCreate($householdId);
    }

}

    

