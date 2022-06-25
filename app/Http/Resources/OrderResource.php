<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\PigeonResource;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'code' => $this->code,
            'user_id' => $this->user_id,
            'pigeon_id' => $this->pigeon_id,
            'distance' => $this->distance,
            'deadline' => $this->deadline,
            'cost_per_km' => $this->cost_per_km,
            'total_cost' => $this->total_cost,
            'status' => $this->status,
            'note' => $this->note,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'pigeon' => PigeonResource::collection($this->whenLoaded('pigeon')),
            'user' => UserResource::collection($this->whenLoaded('user')),
        ];
    }
}
