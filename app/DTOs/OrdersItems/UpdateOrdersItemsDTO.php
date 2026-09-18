<?php

namespace App\DTOs\OrdersItems;

use Illuminate\Http\Request;

readonly class UpdateOrdersItemsDTO
{
    public function __construct(
        public ?int $quantity = null,
        public ?float $price = null,
        public ?float $subtotal = null,
        public ?string $details = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            quantity: isset($data['quantity']) ? (int) $data['quantity'] : null,
            price:    isset($data['price']) ? (float) $data['price'] : null,
            subtotal: isset($data['subtotal']) ? (float) $data['subtotal'] : null,
            details:  $data['details'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'quantity' => $this->quantity,
            'price'    => $this->price,
            'subtotal' => $this->subtotal,
            'details'  => $this->details,
        ], fn ($v) => !is_null($v));
    }
}