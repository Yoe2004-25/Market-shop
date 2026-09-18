<?php

namespace App\DTOs\Products;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class UpdateProductDTO
{
    public function __construct(
        public ?int $category_id = null,
        public ?int $brand_id = null,
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $description = null,
        public ?float $price = null,
        public ?float $discount = null,
        public ?int $stock = null,
        public ?string $sku = null,
        public ?UploadedFile $image = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            category_id: isset($data['category_id']) ? (int) $data['category_id'] : null,
            brand_id:    isset($data['brand_id']) ? (int) $data['brand_id'] : null,
            name:        $data['name'] ?? null,
            slug:        isset($data['slug']) ? str($data['slug'])->slug() : null,
            description: $data['description'] ?? null,
            price:       isset($data['price']) ? (float) $data['price'] : null,
            discount:    isset($data['discount']) ? (float) $data['discount'] : null,
            stock:       isset($data['stock']) ? (int) $data['stock'] : null,
            sku:         $data['sku'] ?? null,
            image:       $request->hasFile('image') ? $request->file('image') : null,
            status:      $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'category_id' => $this->category_id,
            'brand_id'    => $this->brand_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'price'       => $this->price,
            'discount'    => $this->discount,
            'stock'       => $this->stock,
            'sku'         => $this->sku,
            'status'      => $this->status,
        ], fn ($v) => !is_null($v));
    }

    public function hasImage(): bool
    {
        return $this->image instanceof UploadedFile;
    }
}