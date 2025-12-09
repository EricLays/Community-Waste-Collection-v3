<?php

namespace App\Services;

use App\Repositories\Contracts\ReportRepository;

class ReportService
{
    public function __construct(
        private ReportRepository $reports
    ) {}

    /**
     * Waste Summary: aggregated pickups grouped by type & status.
     */
    public function wasteSummary(): array
    {
        return $this->reports->wasteSummary();
    }

    /**
     * Payment Summary: totals by status + total revenue.
     * Optionally filter by date range.
     */
    public function paymentSummary(?string $start = null, ?string $end = null): array
    {
        return $this->reports->paymentSummary($start, $end);
    }

    /**
     * Household Pickup & Payment History for a given household.
     */
    public function householdHistory(string $householdId): ?array
    {
        return $this->reports->householdHistory($householdId);
    }
}
