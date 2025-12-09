<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HouseholdResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => (string) $this->_id,
            'owner_name' => $this->owner_name,
            'address'    => $this->address,
            'block'      => $this->block,
            'no'         => $this->no,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(), 
        ];
    }
}
