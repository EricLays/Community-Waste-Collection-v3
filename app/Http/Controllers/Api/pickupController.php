<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pickup\StorePickupRequest;  // ← updated import
use App\Http\Requests\Pickup\SchedulePickupRequest;
use App\Services\WasteService;
use Illuminate\Http\Response;

class PickupController
{
    public function __construct(private WasteService $service) {}

    // GET /api/pickups?status=&type=&household_id=&page=&per_page=
    

    public function index()
    {
        $q = \App\Models\Waste\Waste::query();

        // Guard filters so null/empty values don't exclude everything
        if ($status = request('status')) { $q->where('status', $status); }
        if ($type   = request('type'))   { $q->where('type',   $type);   }
        if ($hid    = request('household_id')) { $q->where('household_id', $hid); }

        $perPage = (int) request('per_page', 15);
        $result  = $q->orderBy('_id','desc')->paginate($perPage);

        return response()->json([
            'data'  => $result->items(),
            'links' => [
                'first' => $result->url(1),
                'last'  => $result->url($result->lastPage()),
                'prev'  => $result->previousPageUrl(),
                'next'  => $result->nextPageUrl(),
            ],
            'meta'  => [
                'current_page' => $result->currentPage(),
                'from'         => $result->firstItem(),
                'last_page'    => $result->lastPage(),
                'path'         => $result->path(),
                'per_page'     => $result->perPage(),
                'to'           => $result->lastItem(),
                'total'        => $result->total(),
            ],
            'success' => true,
        ]);
    }



    // POST /api/pickups

    public function store(StorePickupRequest $request)
    {
        $pickup = $this->service->createPickup($request->validated());

        // force refresh to ensure _id/timestamps present
        $pickup = $pickup->fresh();

        return response()->json(['data' => $pickup], 201);
    }


    // PUT /api/pickups/{id}/schedule
    public function schedule(SchedulePickupRequest $request, string $id)
    {
        $pickup = $this->service->schedule($id, new \DateTimeImmutable($request->validated()['pickup_date']));
        return response()->json(['data' => $pickup], Response::HTTP_OK);
    }

    // PUT /api/pickups/{id}/complete
    public function complete(string $id)
    {
        $pickup = $this->service->complete($id);
        return response()->json(['data' => $pickup], Response::HTTP_OK);
    }

    // PUT /api/pickups/{id}/cancel
    public function cancel(string $id)
    {
        $pickup = $this->service->cancel($id);
        return response()->json(['data' => $pickup], Response::HTTP_OK);
    }
}
