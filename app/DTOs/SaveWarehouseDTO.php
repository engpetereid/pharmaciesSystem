<?php
namespace App\DTOs;

use App\Http\Requests\Warehouse\SaveWarehouseRequest;

class SaveWarehouseDTO
{
    public function __construct(
        public readonly int $quantity,
        public readonly int $pharmacy_id,
        public readonly int $drug_id,
        public readonly int $minimum_quantity,
    ) {}

    public static function fromRequest(
        SaveWarehouseRequest $request
    ): self {
        return new self(
            quantity: $request->quantity,
            pharmacy_id: $request->pharmacy_id,
            drug_id: $request->drug_id,
            minimum_quantity: $request->minimum_quantity,
        );
    }
}
