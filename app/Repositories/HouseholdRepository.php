<?php

namespace App\Repositories;

use App\Models\Household;

class HouseholdRepository
{
    public function create(array $data)
    {
        return Household::create($data);
    }

    public function find($id)
    {
        return Household::find($id);
    }

    public function update($id, array $data)
    {
        $household = Household::find($id);
        if ($household) {
            $household->update($data);
        }
        return $household;
    }

    public function delete($id)
    {
        return Household::destroy($id);
    }

    public function search(array $filters)
    {
        $query = Household::query();

        if (!empty($filters['search'])) {
            $query->where('owner_name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('address', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['block'])) {
            $query->where('block', $filters['block']);
        }

        if (!empty($filters['no'])) {
            $query->where('no', $filters['no']);
        }

        return $query->paginate(10);
    }
}
