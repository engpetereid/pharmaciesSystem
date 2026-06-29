<?php
namespace App\DTOs;

use App\Http\Requests\Drug\CreateDrugRequest;
use App\Http\Requests\Drug\EditDrugRequest;

class SaveDrugDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $price,
        public readonly int $category_id
    ) {}

    public static function fromRequest(
        CreateDrugRequest|EditDrugRequest $request
    ): self {
        return new self(
            name: $request->name,
            price: $request->price,
            category_id: $request->category_id
        );
    }
}
