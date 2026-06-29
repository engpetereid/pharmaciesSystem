<?php

namespace App\Services\Supervisor;

use App\DTOs\SaveInvoiceDTO;
use App\Models\Invoice;
use App\Models\Pharma;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IInvoiceService
{

    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Invoice;

    public function store(SaveInvoiceDTO $dto): Invoice;

    public function update(Invoice $invoice, SaveInvoiceDTO $dto): Invoice;

    public function delete(Invoice $invoice): bool;

    public function supervisorInvoices(Pharma $pharma): Collection;

}
