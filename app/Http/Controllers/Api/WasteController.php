<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\WasteCreateRequest;
use App\Http\Requests\WasteScheduleRequest;
use App\Http\Resources\WasteResource;
use App\Repositories\WasteRepository;
use App\Services\WasteService;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    protected $service;
    protected $repo;

    public function __construct(WasteService $service, WasteRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }

    public function store(WasteCreateRequest $request)
    {
        $waste = $this->service->createPickup($request->validated());
        return new WasteResource($waste);
    }

    public function index(Request $request)
    {
        $wastes = $this->repo->filter($request->all());
        return WasteResource::collection($wastes);
    }

    public function schedule($id, WasteScheduleRequest $request)
    {
        $waste = $this->service->schedulePickup($id, $request->pickup_date);
        return new WasteResource($waste);
    }

    public function complete($id)
    {
        return new WasteResource(
            $this->service->completePickup($id)
        );
    }

    public function cancel($id)
    {
        return new WasteResource(
            $this->service->cancelPickup($id)
        );
    }
}
