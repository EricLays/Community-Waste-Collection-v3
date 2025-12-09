<?php

namespace App\Repositories\Mongo;

use App\Repositories\Contracts\ReportRepository;
use Illuminate\Support\Facades\DB;

final class ReportMongoRepository implements ReportRepository
{
    public function wasteSummary(): array
    {
        $pipeline = [
            [
                '$group' => [
                    '_id'   => ['type' => '$type', 'status' => '$status'],
                    'count' => ['$sum' => 1],
                ],
            ],
            [
                '$sort' => ['_id.type' => 1, '_id.status' => 1],
            ],
        ];

        // Use Laravel MongoDB driver to run raw aggregation
        $result = DB::connection('mongodb')
            ->collection('wastes')
            ->raw(fn($c) => $c->aggregate($pipeline)->toArray());

        return $result;
    }

    public function paymentSummary(?string $start = null, ?string $end = null): array
    {
        $match = [];
        if ($start || $end) {
            $dateFilter = [];
            if ($start) $dateFilter['$gte'] = new \MongoDB\BSON\UTCDateTime(strtotime($start) * 1000);
            if ($end)   $dateFilter['$lte'] = new \MongoDB\BSON\UTCDateTime(strtotime($end) * 1000);
            $match['payment_date'] = $dateFilter;
        }

        $pipeline = [];
        if (!empty($match)) {
            $pipeline[] = ['$match' => $match];
        }

        $pipeline[] = [
            '$group' => [
                '_id'          => '$status',              // pending|paid|failed
                'count'        => ['$sum' => 1],
                'amount_total' => ['$sum' => '$amount'],
            ],
        ];

        $groups = DB::connection('mongodb')
            ->collection('payments')
            ->raw(fn($c) => $c->aggregate($pipeline)->toArray());

        // Compute total revenue (sum of paid amounts)
        $totalRevenue = 0;
        foreach ($groups as $g) {
            if (($g['_id'] ?? null) === 'paid') {
                $totalRevenue += (int) ($g['amount_total'] ?? 0);
            }
        }

        return [
            'groups'        => $groups,
            'total_revenue' => $totalRevenue,
        ];
    }

    public function householdHistory(string $householdId): ?array
    {
        $wastes = DB::connection('mongodb')
            ->collection('wastes')
            ->where('household_id', $householdId)
            ->orderBy('_id', 'desc')
            ->get()
            ->toArray();

        $payments = DB::connection('mongodb')
            ->collection('payments')
            ->where('household_id', $householdId)
            ->orderBy('_id', 'desc')
            ->get()
            ->toArray();

        if (empty($wastes) && empty($payments)) {
            return null;
        }

        return [
            'household_id' => $householdId,
            'pickups'      => $wastes,
            'payments'     => $payments,
        ];
    }
}
