<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\PaymentService;
use App\Support\ApiResponse;

class PaymentController
{
    public function __construct(
        private PaymentService $service,
        private PaymentRepositoryInterface $repo
    ) {}

    // POST /api/payments
    public function store(StorePaymentRequest $request)
    {
        $payment = $this->service->create($request->validated());
        return ApiResponse::ok(new PaymentResource($payment), [], 201);
    }

    // GET /api/payments
    public function index()
    {
        $filters = request()->only(['status','household_id','from','to']);
        $items = $this->repo->paginate($filters, 15);
        return PaymentResource::collection($items)->additional(['success' => true]);
    }

    // PUT /api/payments/{id}/confirm
    public function confirm(ConfirmPaymentRequest $request, string $id)
    {
        $payment = $this->repo->find($id);
        if (!$payment) return ApiResponse::err('Payment not found', [], 404);

        // Optionally override payment_date via request
        if ($request->filled('payment_date')) {
            $payment->payment_date = $request->date('payment_date');
        }

        $payment = $this->service->confirm($payment);
        return ApiResponse::ok(new PaymentResource($payment));
    }
}
