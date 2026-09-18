<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrdersResource; 
use App\Http\Resources\ProudctResource; 
class OrdersItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
            'id'=> $this->id,
            'order'=> new OrdersResource($this->whenLoaded('order')),
            'product'=> new ProudctResource($this->whenLoaded('product')),
            'price'=> $this->price,
            'quantity'=> $this->quantity,
            'subtotal'=> $this->subtotal,
            'details'=> $this->details,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}