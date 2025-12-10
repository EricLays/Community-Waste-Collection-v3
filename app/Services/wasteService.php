<?php

namespace App\Services;

use App\Repositories\WasteRepository;
use App\Models\Waste\WasteElectronic;
use Exception;

class WasteService
{
    protected $repo;

    public function __construct(WasteRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createPickup(array $data)
    {
        $data['status'] = 'pending';
        // business rule: electronic requires safety_check
        if ($data['type'] === 'electronic' && empty($data['safety_check'])) {
            throw new Exception("Electronic waste requires safety_check before creation.");
        }

        return $this->repo->create($data);
    }

    public function schedulePickup(string $id, string $pickupDate)
    {
        $waste = $this->repo->find($id);

        // electronic rule
        if ($waste instanceof WasteElectronic && !$waste->safety_check) {
            throw new Exception("Electronic waste cannot be scheduled without safety_check.");
        }

        $waste->pickup_date = $pickupDate;
        $waste->status = 'scheduled';

        return $this->repo->save($waste);
    }

    public function completePickup(string $id)
    {
        $waste = $this->repo->find($id);
        $waste->status = 'completed';

        return $this->repo->save($waste);
    }

    public function cancelPickup(string $id)
    {
        $waste = $this->repo->find($id);
        $waste->status = 'canceled';

        return $this->repo->save($waste);
    }
}
