<?php

namespace App\Repositories\Contracts;

interface ReportRepository
{
    /**
     * Waste summary grouped by {type, status}.
     */
    public function wasteSummary(): array;

    /**
     * Payment summary: totals by status + total revenue.
     * Optional date range filter (YYYY-MM-DD).
     */
    public function paymentSummary(?string $start = null, ?string $end = null): array;

    /**
     * Household history: pickups + payments for one household.
     */
    public function householdHistory(string $householdId): ?array;
}
