<?php

namespace App\Repositories\Contracts;

use App\Models\Waste\Waste;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

interface WasteRepositoryInterface
{
    public function create(Waste $waste): Waste;
    public function find(string $id): ?Waste;
    public function findOrFail(string $id): Waste;
    public function update(Waste $waste): Waste;

    /**
     * Optional: expose a query builder when you need custom queries (e.g., scheduled job).
     */
    public function query(): Builder;

    /**
     * Filters: status, type, household_id. Paginated.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
