<?php

namespace App\DTOs\Orders;

use Illuminate\Http\Request;

readonly class UpdateOrdersDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $status = null,
        public ?string $payment_status = null,
        public ?float $total = null,
        public ?float $shipping_cost = null,
        public ?float $tax = null,
        public ?float $grand_total = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name:           $data['name'] ?? null,
            status:         $data['status'] ?? null,
            payment_status: $data['payment_status'] ?? null,
            total:          isset($data['total']) ? (float) $data['total'] : null,
            shipping_cost:  isset($data['shipping_cost']) ? (float) $data['shipping_cost'] : null,
            tax:            isset($data['tax']) ? (float) $data['tax'] : null,
            grand_total:    isset($data['grand_total']) ? (float) $data['grand_total'] : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name'           => $this->name,
            'status'         => $this->status,
            'payment_status' => $this->payment_status,
            'total'          => $this->total,
            'shipping_cost'  => $this->shipping_cost,
            'tax'            => $this->tax,
            'grand_total'    => $this->grand_total,
        ], fn ($v) => !is_null($v));
    }
}