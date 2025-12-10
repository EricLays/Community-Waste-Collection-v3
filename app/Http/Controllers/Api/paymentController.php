<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    // POST /api/payments
    public function store(StorePaymentRequest $request)
    {
        $payment = $this->service->create($request->validated());
        return new PaymentResource($payment);
    }

    // GET /api/payments
    public function index(Request $request)
    {
        $filters = [
            'status'        => $request->status,
            'household_id'  => $request->household_id,
            'date_from'     => $request->date_from,
            'date_to'       => $request->date_to,
        ];

        $payments = $this->service->list($filters);

        return PaymentResource::collection($payments);
    }

    // PUT /api/payments/{id}/confirm
    public function confirm($id)
    {
        $payment = $this->service->confirm($id);
        return new PaymentResource($payment);
    }
}
