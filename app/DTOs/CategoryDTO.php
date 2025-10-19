<?php

namespace App\DTOs;

use App\Models\Category;

class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
        ];
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            color: $category->color
        );
    }
}
