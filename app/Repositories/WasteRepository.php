<?php

namespace App\Repositories;

use App\Models\Waste\Waste;

class WasteRepository
{
    public function create(array $data)
    {
        return Waste::create($data);
    }

    public function find(string $id)
    {
        return Waste::findOrFail($id);
    }

    public function filter(array $filters)
    {
        $query = Waste::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['household_id'])) {
            $query->where('household_id', $filters['household_id']);
        }

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function save(Waste $waste)
    {
        $waste->save();
        return $waste;
    }
}
