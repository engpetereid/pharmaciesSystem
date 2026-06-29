<?php
namespace App\DTOs;

use App\Http\Requests\Order\SaveOrderRequest;

class SaveOrderDTO
{
    public function __construct(
        public readonly int $pharmacy_id,
        public readonly int $drug_id ,
        public readonly int $quantity,
        public readonly bool $accepted,
    ) {}

    public static function fromRequest(
        SaveOrderRequest $request
    ): self {
        return new self(
            pharmacy_id: $request->pharmacy_id,
            drug_id: $request->drug_id,
            quantity: $request->quantity,
            accepted: $request->accepted
        );
    }
}
