<?php

namespace App\Repositories\Mongo;

use App\Models\Household;
use App\Repositories\Contracts\HouseholdRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class HouseholdMongoRepository implements HouseholdRepositoryInterface
{
    public function create(array $data): Household
    {
        return Household::query()->create($data);
    }

    public function find(string $id): ?Household
    {
        return Household::query()->find($id);
    }

    public function findOrFail(string $id): Household
    {
        return Household::query()->findOrFail($id);
    }

    public function update(Household $household, array $data): Household
    {
        $household->fill($data);
        $household->save();

        return $household;
    }

    public function delete(Household $household): bool
    {
        return (bool) $household->delete();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Household::query();

        // Simple search by owner_name/address
        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $q->where(function ($qq) use ($term) {
                $qq->where('owner_name', 'like', "%{$term}%")
                   ->orWhere('address', 'like', "%{$term}%");
            });
        }

        if (!empty($filters['block'])) {
            $q->where('block', $filters['block']);
        }
        if (!empty($filters['no'])) {
            $q->where('no', $filters['no']);
        }

        return $q->orderBy('_id', 'desc')->paginate($perPage);
    }
}
