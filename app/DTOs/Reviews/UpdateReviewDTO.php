<?php

namespace App\DTOs\Reviews;

use Illuminate\Http\Request;

readonly class UpdateReviewDTO
{
    public function __construct(
        public ?int $rating = null,
        public ?string $comment = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            rating:  isset($data['rating']) ? (int) $data['rating'] : null,
            comment: $data['comment'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'rating'  => $this->rating,
            'comment' => $this->comment,
        ], fn ($v) => !is_null($v));
    }
}