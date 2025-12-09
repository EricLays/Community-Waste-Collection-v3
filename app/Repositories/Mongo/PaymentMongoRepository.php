<?php

namespace App\Repositories\Mongo;

use App\Models\Payment;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class PaymentMongoRepository implements PaymentRepositoryInterface
{
    public function create(array $data): Payment
    {
        return Payment::query()->create($data);
    }

    public function find(string $id): ?Payment
    {
        return Payment::query()->find($id);
    }

    public function findOrFail(string $id): Payment
    {
        return Payment::query()->findOrFail($id);
    }

    public function update(Payment $payment): Payment
    {
        $payment->save();
        return $payment;
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $q = Payment::query();

        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }
        if (!empty($filters['household_id'])) {
            $q->where('household_id', $filters['household_id']);
        }

        // Optional date range filter on payment_date
        if (!empty($filters['start'])) {
            $q->where('payment_date', '>=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $q->where('payment_date', '<=', $filters['end']);
        }

        return $q->orderBy('_id', 'desc')->paginate($perPage);
    }

    public function hasUnpaid(string $householdId): bool
    {
        return Payment::query()
            ->where('household_id', $householdId)
            ->where('status', 'pending')
            ->exists();
    }
}
