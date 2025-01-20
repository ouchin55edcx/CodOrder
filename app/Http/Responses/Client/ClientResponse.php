<?php

namespace App\Http\Responses\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResponse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'state' => $this->state,
            'city' => $this->city,
            'address' => $this->address,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString()
        ];
    }
}
