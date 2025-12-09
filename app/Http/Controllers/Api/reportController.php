<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Reporting endpoints:
 * - GET /api/reports/waste-summary
 * - GET /api/reports/payment-summary
 * - GET /api/reports/households/{id}/history
 *
 * All responses are JSON.
 */
class ReportController
{
    public function __construct(
        private ReportService $service
    ) {
        // If you plan to protect reports with auth (optional):
        // $this->middleware('auth:sanctum')->only(['wasteSummary','paymentSummary','householdHistory']);
    }

    /**
     * Waste Summary:
     * Aggregated pickups grouped by waste type & status.
     * Query params (optional): none, or add pagination/filter if your service supports it.
     */
    public function wasteSummary(Request $request)
    {
        $summary = $this->service->wasteSummary();
        return response()->json([
            'data' => $summary,
        ], Response::HTTP_OK);
    }

    /**
     * Payment Summary:
     * Totals by status + total revenue.
     * Query params (optional): start, end (YYYY-MM-DD) for date range filters if implemented.
     */
    public function paymentSummary(Request $request)
    {
        $summary = $this->service->paymentSummary(
            start: $request->query('start'),
            end:   $request->query('end')
        );

        return response()->json([
            'data' => $summary,
        ], Response::HTTP_OK);
    }

    /**
     * Household Pickup & Payment History:
     * GET /api/reports/households/{id}/history
     *
     * @param  string  $id  Household ID (Mongo ObjectId string)
     */
    public function householdHistory(string $id)
    {
        $history = $this->service->householdHistory($id);

        if (!$history) {
            return response()->json([
                'error' => [
                    'code'    => Response::HTTP_NOT_FOUND,
                    'message' => 'Household not found or no history available',
                ],
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $history,
        ], Response::HTTP_OK);
    }
}
