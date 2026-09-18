<?php

namespace App\DTOs\Carts;

use Illuminate\Http\Request;

readonly class UpdateCartDTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $content = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name:    $data['name'] ?? null,
            content: $data['content'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name'    => $this->name,
            'content' => $this->content,
        ], fn ($v) => !is_null($v));
    }
}