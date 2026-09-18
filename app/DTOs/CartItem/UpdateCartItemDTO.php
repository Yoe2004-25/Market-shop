<?php

namespace App\DTOs\CartItem;

use Illuminate\Http\Request;

readonly class UpdateCartItemDTO
{
    public function __construct(
        public ?int $quantity = null,
        public ?float $price = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            quantity: isset($data['quantity']) ? (int) $data['quantity'] : null,
            price:    isset($data['price']) ? (float) $data['price'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'quantity' => $this->quantity,
            'price'    => $this->price,
        ], fn ($v) => !is_null($v));
    }
}