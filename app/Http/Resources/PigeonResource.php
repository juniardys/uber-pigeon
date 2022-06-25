<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Http\Resources\TimeoffResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PigeonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'speed' => $this->speed,
            'range' => $this->range,
            'cost' => $this->cost,
            'downtime' => $this->downtime,
            'weight' => $this->weight,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'timeoffs' => TimeoffResource::collection($this->whenLoaded('timeoffs')),
        ];
    }
}
