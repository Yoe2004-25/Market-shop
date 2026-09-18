<?php

namespace App\DTOs\Category;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

readonly class CategoryDTO
{
    public function __construct(
        public string $name,
        public string $slug,
        public ?string $description,
        public ?UploadedFile $image,
        public bool $status,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $data = $request->validated();

        return new self(
            name:        $data['name'],
            slug:        $data['slug'] ?? str($data['name'])->slug(),
            description: $data['description'] ?? null,
            image:       $request->hasFile('image') ? $request->file('image') : null,
            status:      (bool) ($data['status'] ?? true),
        );
    }

   
    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'status'      => $this->status,
        ];
    }

    public function hasImage(): bool
    {
        return $this->image instanceof UploadedFile;
    }
}