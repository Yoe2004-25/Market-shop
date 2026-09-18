<?php

namespace App\DTOs\Carts;

use Illuminate\Http\Request;

readonly class CreateCartDTO
{
    public function __construct(
        public int $user_id,
        public string $name,
        public string $content = '',
    ) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        return new self(
            user_id: $userId,
            name:    $data['name'],
            content: $data['content'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'name'    => $this->name,
            'content' => $this->content,
        ];
    }
}