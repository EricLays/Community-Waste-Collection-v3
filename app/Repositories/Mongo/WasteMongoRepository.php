<?php

namespace App\Repositories\Mongo;

use App\Models\Waste\Waste;
use App\Repositories\Contracts\WasteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class WasteMongoRepository implements WasteRepositoryInterface
{
    public function create(Waste $waste): Waste
    {
        $waste->save();
        return $waste;
    }

    public function find(string $id): ?Waste
    {
        return Waste::query()->find($id);
    }

    public function findOrFail(string $id): Waste
    {
        return Waste::query()->findOrFail($id);
    }

    public function update(Waste $waste): Waste
    {
        $waste->save();
        return $waste;
    }

    public function query(): Builder
    {
        return Waste::query();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Waste::query();

        foreach (['status', 'type', 'household_id'] as $f) {
            if (!empty($filters[$f])) {
                $q->where($f, $filters[$f]);
            }
        }

        return $q->orderBy('_id', 'desc')->paginate($perPage);
    }
}
