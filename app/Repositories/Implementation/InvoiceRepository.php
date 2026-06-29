<?php
namespace App\Repositories\Implementation;
use App\Models\Invoice;
use App\DTOs\SaveInvoiceDTO;
use App\Models\Pharma;
use App\Repositories\IInvoiceRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InvoiceRepository implements IInvoiceRepository{
    public function store(SaveInvoiceDTO $dto): Invoice
    {
        return Invoice::create([
            'pharmacy_id' => $dto->pharmacy_id,
            'price' => $dto->price,
            'date' => $dto->date
        ]);
    }
    public function update(Invoice $invoice, SaveInvoiceDTO $dto): Invoice
    {
        $invoice->update([
            'pharmacy_id' => $dto->pharmacy_id,
            'price' => $dto->price,
            'date' => $dto->date
        ]);
        return $invoice->refresh();
    }
    public function delete(Invoice $invoice): bool
    {
        $invoice->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Invoice::with(['pharmacy', 'items.drug'])->paginate(25);
    }

    public function findById(int $id): Invoice
    {
        return Invoice::findOrFail($id);
    }

    public function all(): Collection
    {
        return Invoice::all();
    }

    public function updatePrice(Invoice $invoice , int $total): bool
    {
        return $invoice->update(['price' => $total]);
    }
    public function supervisorInvoices(Pharma $pharma): Collection
    {
        return Invoice::with(['pharmacy','items.drug'])->where('pharmacy_id',$pharma->id)->get();
    }
}
