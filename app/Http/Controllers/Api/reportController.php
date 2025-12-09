<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pickup\StorePickupRequest;
use App\Http\Requests\Pickup\SchedulePickupRequest;
use App\Repositories\Contracts\WasteRepositoryInterface;
use App\Services\WasteService;
use App\Support\ApiResponse;
use App\Http\Resources\PickupResource;
use Carbon\Carbon;

class PickupController
{
    public function __construct(
        private WasteService $service,
        private WasteRepositoryInterface $repo
    ) {}

    // POST /api/pickups  (Create pickup)
    public function store(StorePickupRequest $request)
    {
        try {
            $pickup = $this->service->create($request->validated());
            return ApiResponse::ok(new PickupResource($pickup), [], 201);
        } catch (\DomainException $e) {
            return ApiResponse::err($e->getMessage(), [], 422);
        }
    }

    // GET /api/pickups  (List with filters)
    public function index()
    {
        $filters = request()->only(['status','type','household_id','pickup_date_from','pickup_date_to']);
        $items = $this->repo->paginate($filters, 15);
        // Wrap paginator with resource collection for consistent shape
        return PickupResource::collection($items)->additional(['success' => true]);
    }

    // PUT /api/pickups/{id}/schedule
    public function schedule(SchedulePickupRequest $request, string $id)
    {
        $pickup = $this->repo->find($id);
        if (!$pickup) return ApiResponse::err('Pickup not found', [], 404);

        // For electronic type, clients can pass safety_check=true here
        if ($request->has('safety_check')) {
            $pickup->safety_check = $request->boolean('safety_check');
        }

        try {
            $pickup = $this->service->schedule($pickup, Carbon::parse($request->string('pickup_date')));
            return ApiResponse::ok(new PickupResource($pickup));
        } catch (\DomainException $e) {
            return ApiResponse::err($e->getMessage(), [], 422);
        }
    }

    // PUT /api/pickups/{id}/complete
    public function complete(string $id)
    {
        $pickup = $this->repo->find($id);
        if (!$pickup) return ApiResponse::err('Pickup not found', [], 404);

        $pickup = $this->service->complete($pickup);
        return ApiResponse::ok(new PickupResource($pickup));
    }

    // PUT /api/pickups/{id}/cancel
    public function cancel(string $id)
    {
        $pickup = $this->repo->find($id);
        if (!$pickup) return ApiResponse::err('Pickup not found', [], 404);

        $pickup = $this->service->cancel($pickup);
        return ApiResponse::ok(new PickupResource($pickup));
    }
}
