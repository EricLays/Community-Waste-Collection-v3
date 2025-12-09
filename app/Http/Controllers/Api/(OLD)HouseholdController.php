<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;                  // ✅ import base Controller
use App\Services\HouseholdService;                   // ✅ your service
use App\Repositories\Contracts\HouseholdRepositoryInterface; // ✅ your repo interface
use App\Http\Requests\StoreHouseholdRequest;         // if you use FormRequest
use App\Http\Resources\HouseholdResource;            // if you use API Resources
use App\Models\Household;  



class HouseholdController
{
    public function __construct(
        private HouseholdService $service,
        private HouseholdRepositoryInterface $repo
    ) {}

    // POST /api/households
    public function store(StoreHouseholdRequest $request)
    {
        $household = $this->service->create($request->validated());
        return ApiResponse::ok(new HouseholdResource($household), [], 201);
    }

    // GET /api/households
    public function index()
    {
        $filters = request()->only(['block','no','search']);
        $items = $this->repo->paginate($filters, 15);
        return HouseholdResource::collection($items)->additional(['success' => true]);
    }

    // GET /api/households/{id}
    public function show(string $id)
    {
        $model = $this->repo->find($id);
        if (!$model) return ApiResponse::err('Household not found', [], 404);
        return ApiResponse::ok(new HouseholdResource($model));
    }

    // PUT /api/households/{id}
    public function update(UpdateHouseholdRequest $request, string $id)
    {
        $model = $this->repo->find($id);
        if (!$model) return ApiResponse::err('Household not found', [], 404);

        $updated = $this->service->update($model, $request->validated());
        return ApiResponse::ok(new HouseholdResource($updated));
    }

    // DELETE /api/households/{id}
    public function destroy(string $id)
    {
        $model = $this->repo->find($id);
        if (!$model) return ApiResponse::err('Household not found', [], 404);

        $this->repo->delete($model);
        // 204 means OK but no body
        return response()->json(['success' => true], 204);
    }
}
