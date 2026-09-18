<?php

namespace App\DTOs\Reviews;

use Illuminate\Http\Request;

readonly class CreateReviewDTO
{
    public function __construct( public int $user_id, public int $product_id, public int $rating, public ?string $comment,) {}

    public static function fromRequest(Request $request, int $userId): self
    {
        $data = $request->validated();

        return new self(
            user_id:    $userId,
            product_id: (int) $data['product_id'],
            rating:     (int) $data['rating'],
            comment:    $data['comment'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'user_id'    => $this->user_id,
            'product_id' => $this->product_id,
            'rating'     => $this->rating,
            'comment'    => $this->comment,
        ];
    }
}