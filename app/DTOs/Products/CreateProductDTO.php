<?php

namespace App\DTOs\Products;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class CreateProductDTO
{
    public function __construct(
        public int $category_id,
        public ?int $brand_id,
        public int $user_id,
        public string $name,
        public string $slug,
        public string $description,
        public float $price,
        public float $discount,
        public int $stock,
        public string $sku,
        public ?UploadedFile $image,
        public string $status,
    ) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        return new self(
            category_id: (int) $data['category_id'],
            brand_id:    isset($data['brand_id']) ? (int) $data['brand_id'] : null,
            user_id:     $userId,
            name:        $data['name'],
            slug:        $data['slug'] ?? str($data['name'])->slug(),
            description: $data['description'],
            price:       (float) $data['price'],
            discount:    (float) ($data['discount'] ?? 0),
            stock:       (int) ($data['stock'] ?? 1),
            sku:         (string) $data['sku'],
            image:       $request->hasFile('image') ? $request->file('image') : null,
            status:      $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'brand_id'    => $this->brand_id,
            'user_id'     => $this->user_id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'price'       => $this->price,
            'discount'    => $this->discount,
            'stock'       => $this->stock,
            'sku'         => $this->sku,
            'status'      => $this->status,
        ];
    }

    public function hasImage(): bool
    {
        return $this->image instanceof UploadedFile;
    }
}