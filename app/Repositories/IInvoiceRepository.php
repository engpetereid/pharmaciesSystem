<?php

namespace App\Repositories;

use App\Models\Invoice;
use App\DTOs\SaveInvoiceDTO;
use App\Models\Pharma;
use Illuminate\Support\Collection;

interface IInvoiceRepository
{
    public function store(SaveInvoiceDTO $dto): Invoice;

    public function update(Invoice $invoice, SaveInvoiceDTO $dto): Invoice;

    public function delete(Invoice $invoice): bool;

    public function paginate();

    public function findById(int $id): Invoice;

    public function all(): Collection;

    public function updatePrice(Invoice $invoice , int $total): bool;

    public function supervisorInvoices(Pharma $pharma ): Collection;

}
