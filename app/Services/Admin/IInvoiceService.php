<?php

namespace App\Services\Admin;

use App\DTOs\SaveInvoiceDTO;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

interface IInvoiceService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Invoice;

    public function store(SaveInvoiceDTO $dto): Invoice;

    public function update(Invoice $invoice, SaveInvoiceDTO $dto): Invoice;

    public function delete(Invoice $invoice): bool;
}
