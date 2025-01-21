<?php

namespace App\Http\Responses\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResponse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'is_active' => $this->status === 'active',
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString()
        ];
    }
}
