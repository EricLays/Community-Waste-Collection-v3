<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseholdRequest;
use App\Http\Resources\HouseholdResource;
use App\Services\HouseholdService; // if you use a service layer

class HouseholdController
{
    public function __construct(private HouseholdService $service) {}

    public function store(StoreHouseholdRequest $request)
    {
        $household = $this->service->create($request->validated());

        return (new HouseholdResource($household))
            ->response()
            ->setStatusCode(201);
    }

    public function index()
    {
        // if you return a paginator
        $result = $this->service->list(request()->all(), (int) request('per_page', 15));
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
}
