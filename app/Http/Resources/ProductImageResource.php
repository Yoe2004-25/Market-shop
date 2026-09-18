<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'image' => $this->image,
            'image_url' => $this->image_url, 
            'is_primary' => (bool) $this->primary,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}