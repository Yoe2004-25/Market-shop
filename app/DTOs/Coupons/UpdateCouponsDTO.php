<?php

namespace App\DTOs\Coupons;

use Illuminate\Http\Request;

readonly class UpdateCouponsDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $code = null,
        public ?string $type = null,
        public ?float $value = null,
        public ?string $expire_date = null,
        public ?int $usage_limit = null,
        public ?string $status = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name:        $data['name'] ?? null,
            code:        isset($data['code']) ? strtoupper($data['code']) : null,
            type:        $data['type'] ?? null,
            value:       isset($data['value']) ? (float) $data['value'] : null,
            expire_date: $data['expire_date'] ?? null,
            usage_limit: isset($data['usage_limit']) ? (int) $data['usage_limit'] : null,
            status:      $data['status'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name'        => $this->name,
            'code'        => $this->code,
            'type'        => $this->type,
            'value'       => $this->value,
            'expire_date' => $this->expire_date,
            'usage_limit' => $this->usage_limit,
            'status'      => $this->status,
        ], fn ($v) => !is_null($v));
    }
}