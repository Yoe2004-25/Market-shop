<?php

namespace App\DTOs\Wishlists;

use Illuminate\Http\Request;

readonly class UpdateWishlistDTO
{
    public function __construct(
        public ?int $product_id = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            product_id: isset($data['product_id']) ? (int) $data['product_id'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'product_id' => $this->product_id,
        ], fn ($v) => !is_null($v));
    }
}