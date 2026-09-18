<?php

namespace App\DTOs\OrdersItems;

use Illuminate\Http\Request;

readonly class CreateOrdersItemsDTO
{
    public function __construct(
        public int $order_id,
        public int $product_id,
        public int $quantity,
        public float $price,
        public float $subtotal,
        public string $details,
        public int $user_id,
    ) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        return new self(
            order_id:   (int) $data['order_id'],
            product_id: (int) $data['product_id'],
            quantity:   (int) $data['quantity'],
            price:      (float) $data['price'],
            subtotal:   (float) ($data['subtotal'] ?? ($data['price'] * $data['quantity'])),
            details:    $data['details'],
            user_id:    $userId,
        );
    }

    public function toArray(): array
    {
        return [
            'order_id'   => $this->order_id,
            'product_id' => $this->product_id,
            'quantity'   => $this->quantity,
            'price'      => $this->price,
            'subtotal'   => $this->subtotal,
            'details'    => $this->details,
            'user_id'    => $this->user_id,
        ];
    }
}