<?php

namespace App\DTOs\Brands;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class UpdateBrandsDTO
{
    public function __construct(
        public ?string $name = null,
        public ?UploadedFile $logo = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name: $data['name'] ?? null,
            logo: $request->hasFile('logo') ? $request->file('logo') : null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'logo' => $this->logo,
        ], fn ($v) => !is_null($v));
    }
}