<?php
namespace App\DTOs;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\EditCategoryRequest;

class SaveCategoryDTO
{
    public function __construct(
        public readonly string $name,
    ) {}

    public static function fromRequest(
        CreateCategoryRequest|EditCategoryRequest $request
    ): self {
        return new self(
            name: $request->name
        );
    }
}
