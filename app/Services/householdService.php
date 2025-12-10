<?php

namespace App\Services;

use App\Models\Household;
use App\Repositories\Contracts\HouseholdRepositoryInterface;
use Illuminate\Support\Arr;

class HouseholdService
{
    public function __construct(
        private HouseholdRepositoryInterface $households
    ) {}

    /**
     * Create a new household.
     *
     * @param  array $data  validated payload (owner_name, address, block?, no?)
     * @return Household
     */
    public function create(array $data): Household
    {
        // Repository handles persistence (Mongo-backed Eloquent or query)
        return $this->households->create($data);
    }

    /**
     * List households with optional filters (search, block, no) and pagination.
     *
     * @param  array $filters
     * @param  int   $perPage
     */
    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->households->paginate($filters, $perPage);
    }

    /**
     * Get single household.
     */
    public function find(string $id): ?Household
    {
        return $this->households->find($id);
    }

    /**
     * Update household.
     */
    public function update(string $id, array $data): Household
    {
        $household = $this->households->find($id);
        $allowed   = Arr::only($data, ['owner_name','address','block','no']);
        return $this->households->update($household, $allowed);
    }

    /**
     * Delete household (or soft delete if implemented).
     */
    public function delete(string $id): bool
    {
        $household = $this->households->find($id);
        return $this->households->delete($household);
    }
}
