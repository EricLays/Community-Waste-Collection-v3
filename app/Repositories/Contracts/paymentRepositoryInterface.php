<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PaymentRepositoryInterface
{
    public function create(array $data): Payment;
    public function find(string $id): ?Payment;
    public function findOrFail(string $id): Payment;
    public function update(Payment $payment): Payment;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function hasUnpaid(string $householdId): bool;
}
