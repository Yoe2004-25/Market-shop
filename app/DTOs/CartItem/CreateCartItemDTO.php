<?php

namespace App\DTOs\CartItem;

use Illuminate\Http\Request;

readonly class CreateCartItemDTO
{
    public function __construct(
        public int $cart_id,
        public int $product_id,
        public int $quantity,
        public float $price,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            cart_id:    (int) $data['cart_id'],
            product_id: (int) $data['product_id'],
            quantity:   (int) ($data['quantity'] ?? 1),
            price:      (float) $data['price'],
        );
    }

    public function toArray(): array
    {
        return [
            'cart_id'    => $this->cart_id,
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,
            'price'      => $this->price,
        ];
    }
}