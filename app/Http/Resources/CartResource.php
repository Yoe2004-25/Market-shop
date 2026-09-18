<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cart_itemsResource;
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'cart_items'=>Cart_itemsResource::collection($this->whenLoaded('cart_items')),
            'name'=>$this->name , 
            'content'=>$this->content, 
            'created_at'=>$this->created_at?->format('Y-m-d H:m:s') , 
        ];
    }
}