<?php

namespace App\Repositories;

use App\Models\Waste\Waste;
use App\Models\Payment;

class ReportRepository
{
    // WASTE SUMMARY
    public function wasteSummary()
    {
        return Waste::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => [
                            'type' => '$type',
                            'status' => '$status'
                        ],
                        'count' => ['$sum' => 1]
                    ]
                ],
                [
                    '$sort' => ['_id.type' => 1]
                ]
            ]);
        });
    }

    // PAYMENT SUMMARY
    public function paymentSummary()
    {
        $statusSummary = Payment::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$status',
                        'total_amount' => ['$sum' => '$amount'],
                        'count' => ['$sum' => 1]
                    ]
                ]
            ]);
        });

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        return [
            'status_summary' => $statusSummary,
            'total_revenue' => $totalRevenue
        ];
    }

    // HOUSEHOLD PICKUP & PAYMENT HISTORY
    public function householdHistory($householdId)
    {
        $pickups = Waste::where('household_id', $householdId)
            ->orderBy('created_at', 'desc')
            ->get();

        $payments = Payment::where('household_id', $householdId)
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'household_id' => $householdId,
            'pickups' => $pickups,
            'payments' => $payments,
        ];
    }
}
