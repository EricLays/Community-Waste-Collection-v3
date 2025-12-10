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
class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    // GET /api/reports/waste-summary
    public function wasteSummary()
    {
        return response()->json($this->service->wasteSummary());
    }

    // GET /api/reports/payment-summary
    public function paymentSummary()
    {
        return response()->json($this->service->paymentSummary());
    }

    // GET /api/reports/households/{id}/history
    public function householdHistory($id)
    {
        return response()->json($this->service->householdHistory($id));
    }
}