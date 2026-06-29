<?php
namespace App\DTOs;

use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use Carbon\Carbon;

class SaveInvoiceDTO
{
    /**
     * @param SaveInvoiceItemDTO[] $items
     */
    public function __construct(
        public readonly int $pharmacy_id,
        public readonly int $price,
        public readonly Carbon $date,
        public readonly array $items,

    ) {}

    public static function fromRequest(
        CreateInvoiceRequest|EditInvoiceRequest $request
    ): self {
        $data = $request->validated();

        return new self(
            pharmacy_id: $data['pharmacy_id'],

            items: collect($data['items'])->map(fn ($item) => new SaveInvoiceItemDTO(
                drug_id: $item['drug_id'],
                quantity: $item['quantity'],
                unit_price: $item['unit_price'] ?? 0,
            ))->toArray(),

            price: $data['price'] ?? 0,

            date: \Carbon\Carbon::parse($data['date'])
        );
    }
}
