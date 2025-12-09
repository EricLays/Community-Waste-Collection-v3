<?php

namespace App\Repositories\Mongo;

use App\Models\Waste\Waste;
use App\Repositories\Contracts\WasteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

final class WasteMongoRepository implements WasteRepositoryInterface
{
    public function create(Waste $waste): Waste
    {
        $waste->save();
        \Log::info('Pickup saved', ['_id' => (string)($waste->_id ?? $waste->id), 'type' => $waste->type, 'status' => $waste->status]);
        return $waste->fresh();
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
        // Start a Mongo collection query on "wastes"
        $query = DB::connection('mongodb')->collection('wastes');

        // Guard filters — apply only if present
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['household_id'])) {
            $query->where('household_id', $filters['household_id']);
        }

        // Sort and paginate
        $query->orderBy('_id', 'desc');

        // Manually paginate Mongo cursor
        $page    = (int) request('page', 1);
        $results = $query->forPage($page, $perPage)->get();
        $total   = DB::connection('mongodb')->collection('wastes')->count(); // or count with same filters

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    
    // public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    // {
    //     $q = Waste::query();

    //     foreach (['status', 'type', 'household_id'] as $f) {
    //         if (!empty($filters[$f])) {
    //             $q->where($f, $filters[$f]);
    //         }
    //     }

    //     return $q->orderBy('_id', 'desc')->paginate($perPage);
    // }

    
    // public function paginate(array $filters = [], int $perPage = 15)
    // {
    //     $q = \App\Models\Waste\Waste::query();   // ← reads from 'wastes' via model
    //     // ...apply filters (guarded; see #2 below)
    //     return $q->orderBy('_id','desc')->paginate($perPage);
    // }

}
