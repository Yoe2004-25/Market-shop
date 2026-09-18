<?php

namespace App\DTOs;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $otp = null,
        public readonly string $client = 'web', // web | mobile | partner
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            email:    $data['email'],
            password: $data['password'],
            otp:      $data['otp'] ?? null,
            client:   $data['client'] ?? 'web',
        );
    }
}