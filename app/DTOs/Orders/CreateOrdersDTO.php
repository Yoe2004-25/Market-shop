<?php

namespace App\DTOs\Orders;

use Illuminate\Http\Request;

readonly class CreateOrdersDTO
{
    public function __construct(
        public int $user_id,
        public ?int $coupon_id,
        public string $name,
        public string $status,
        public string $payment_status,
        public float $total,
        public float $shipping_cost,
        public float $tax,
        public float $grand_total,
    ) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        $total       = (float) $data['total'];
        $shipping    = (float) ($data['shipping_cost'] ?? 0);
        $tax         = (float) ($data['tax'] ?? 0);
        $grandTotal  = (float) ($data['grand_total'] ?? ($total + $shipping + $tax));

        return new self(
            user_id:        $userId,
            coupon_id:      isset($data['coupon_id']) ? (int) $data['coupon_id'] : null,
            name:           $data['name'],
            status:         $data['status'] ?? 'pending',
            payment_status: $data['payment_status'] ?? 'pending',
            total:          $total,
            shipping_cost:  $shipping,
            tax:            $tax,
            grand_total:    $grandTotal,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'        => $this->user_id,
            'coupon_id'      => $this->coupon_id,
            'name'           => $this->name,
            'status'         => $this->status,
            'payment_status' => $this->payment_status,
            'total'          => $this->total,
            'shipping_cost'  => $this->shipping_cost,
            'tax'            => $this->tax,
            'grand_total'    => $this->grand_total,
        ];
    }
}