<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProudctResource;
class Cart_itemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [ 
            'price'=>$this->price , 
            'quantity'=>$this->quantity ,   
            'cart'=>new CartResource($this->whenLoaded('cart')),
            'product'=> new ProudctResource($this->whenLoaded('product')),
            'created_at'=> $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}