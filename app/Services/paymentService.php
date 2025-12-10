<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Carbon;
use App\Models\Payment;
use App\Models\Waste\Waste;


final class PaymentService
{
    public function __construct(
        private PaymentRepositoryInterface $payments
    ) {}

    /**
     * Create a payment manually (linked to a household).
     */
    public function create(array $data)
    {
        // expects: household_id (string), amount (int), status? (default 'pending')
        $payload = array_merge([
            'status'       => 'pending',
            'payment_date' => null,
        ], $data);

        return $this->payments->create($payload);
    }

    /**
     * List payments with filters: status, household_id, date range (start, end), paginate.
     */
    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->payments->paginate($filters, $perPage);
    }

    /**
     * Confirm a payment: set status=paid and payment_date=now().
     */
    public function confirm(string $id)
    {
        $payment = $this->payments->findOrFail($id);
        $payment->status       = 'paid';
        $payment->payment_date = Carbon::now();
        return $this->payments->update($payment);
    }

    /**
     * Domain helper: whether a household has unpaid (pending) payment.
     */
    public function hasUnpaid(string $householdId): bool
    {
        return $this->payments->hasUnpaid($householdId);
    }

    /**
     * Domain helper used by WasteService on complete().
     */
    public function createForHousehold(string $householdId, int $amount)
    {
        return $this->payments->create([
            'household_id' => $householdId,
            'amount'       => $amount,
            'status'       => 'pending',
            'payment_date' => null,
        ]);
    }

    public function createForPickup(Waste $waste): Payment
    {
        $amount = match ($waste->type) {
            'electronic' => 100_000,
            default      => 50_000,
        };
        return Payment::create([
            'household_id' => $waste->household_id,
            'amount'       => $amount,
            'payment_date' => null,
            'status'       => 'pending',
        ]);
    }

}
