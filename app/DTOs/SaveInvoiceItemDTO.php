<?php

namespace App\DTOs;

class SaveInvoiceItemDTO
{
    public function __construct(
        public readonly int $drug_id,
        public readonly int $quantity,
        public readonly int $unit_price,
    ) {}
}
