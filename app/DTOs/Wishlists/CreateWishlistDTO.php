<?php

namespace App\DTOs\Wishlists;

use Illuminate\Http\Request;

readonly class CreateWishlistDTO
{
    public function __construct(
        public int $user_id,
        public int $product_id,
    ) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        return new self(
            user_id:    $userId,
            product_id: (int) $data['product_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
        ];
    }
}