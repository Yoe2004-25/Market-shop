<?php

namespace App\DTOs\Coupons;

use Illuminate\Http\Request;

readonly class CreateCouponsDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public string $type,
        public float $value,
        public ?string $expire_date,
        public int $usage_limit,
        public string $status,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name:        $data['name'],
            code:        strtoupper($data['code']),
            type:        $data['type'],
            value:       (float) $data['value'],
            expire_date: $data['expire_date'] ?? null,
            usage_limit: (int) ($data['usage_limit'] ?? 1),
            status:      $data['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'code'        => $this->code,
            'type'        => $this->type,
            'value'       => $this->value,
            'expire_date' => $this->expire_date,
            'usage_limit' => $this->usage_limit,
            'status'      => $this->status,
        ];
    }
}