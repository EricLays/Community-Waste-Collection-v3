<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\HouseholdRequest;
use App\Http\Resources\HouseholdResource;
use App\Services\HouseholdService;
use App\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHouseholdRequest;


class HouseholdController extends Controller
{
    protected $service;

    public function __construct(HouseholdService $service) {
        $this->service = $service;
    }

    public function index() {
        $data = $this->service->list(request()->all());
        return HouseholdResource::collection($data);
    }

    public function store(HouseholdRequest $request) { 
        $household = $this->service->create($request->validated());
        return new HouseholdResource($household);
    }

    public function show($id) {
        $household = $this->service->detail($id);

        if (!$household) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new HouseholdResource($household);
    }

    public function update(HouseholdRequest $request, $id) {
        $household = $this->service->update($id, $request->validated());

        if (!$household) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new HouseholdResource($household);
    }

    public function destroy($id) {
        $this->service->delete($id);
        return response()->json(['message' => 'Deleted successfully']);
    }

}
