<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use Illuminate\Http\Resources\Json\JsonResource;
 
class PickupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => (string) ($this->_id ?? $this->id),
            'household_id' => $this->household_id,
            'type'         => $this->type,
            'status'       => $this->status,
            'pickup_date'  => optional($this->pickup_date)->toISOString(),
            'safety_check' => $this->safety_check,
            'created_at'   => optional($this->created_at)->toISOString(),
            'updated_at'   => optional($this->updated_at)->toISOString(),
        ];
    }

    // Set status code for newly-created models
    public function withResponse($request, $response)
    {
        if ($this->resource->wasRecentlyCreated) {
            $response->setStatusCode(201);
        }
    }

}
