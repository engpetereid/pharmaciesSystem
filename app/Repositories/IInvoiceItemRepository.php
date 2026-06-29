<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\DTOs\SaveInvoiceItemDTO;
use Illuminate\Support\Collection;

interface IInvoiceItemRepository
{
    public function createMany(Invoice $invoice, array $items): void;

    public function update(InvoiceItem $invoiceItem, SaveInvoiceItemDTO $dto): InvoiceItem;

    public function delete(InvoiceItem $invoiceItem): bool;

    public function paginate();

    public function findById(int $id): InvoiceItem;

    public function all(): Collection;

}
