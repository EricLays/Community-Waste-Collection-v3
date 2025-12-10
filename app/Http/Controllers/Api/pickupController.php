<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\PickupStoreRequest;
use App\Http\Resources\PickupResource;
use App\Repositories\Contracts\WasteRepositoryInterface;
use App\Services\WasteService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;


final class PickupController extends Controller
{
    // ✅ inject your dependencies; Laravel resolves them via the service container
    public function __construct(
        private WasteService $wasteService,
        private WasteRepositoryInterface $wasteRepo,
    ) {}

    public function store(PickupStoreRequest $request)
    {
        $data = $request->validated();
        
        if (!$this->wasteRepo->householdCanCreate($data['household_id'])) {
            return response()->json(['message' => 'Unpaid payments exist'], 422);
        }

        $data['status'] = $data['status'] ?? 'pending';

        // Build the correct subclass (electronic/organic/etc.)
        $waste   = $this->wasteService->makeWaste($data);
        $created = $this->wasteRepo->create($waste);   // ✅ use $waste (local), not $this->waste

        // Return 201 Created (and you can add a Location header if you wish)
        return PickupResource::make($created)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);   // 201
    }

    
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'type', 'household_id']);
        $perPage = (int) $request->query('per_page', 15);
        $pickups = $this->wasteRepo->paginate($filters, $perPage);

        // Returning a resource collection from a paginator includes links & meta
        return PickupResource::collection($pickups);
    }

}
