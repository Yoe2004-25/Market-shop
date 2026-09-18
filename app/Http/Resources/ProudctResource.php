<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductImageResource;
class ProudctResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'slug'=>$this->slug,
            'description'=>$this->description,
            'price'=>(float) $this->price,
            'discount'=> (float) ($this->discount ?? 0),
            'final_price'=> (float) $this->final_price,
            'stock'=> (int) $this->stock,
            'sku'=> $this->sku,
            'image'=> $this->image,
            'image_url'=> $this->image_url,
            'status'=> $this->status,
            'category'=> new CategoryResource($this->whenLoaded('category')),
            'brand'=> new BrandResource($this->whenLoaded('brand')),
            'images'=> ProductImageResource::collection($this->whenLoaded('images')),
            'created_at'=> $this->created_at?->toDateTimeString(),
            'updated_at'=> $this->updated_at?->toDateTimeString(),
        ];
    }
}