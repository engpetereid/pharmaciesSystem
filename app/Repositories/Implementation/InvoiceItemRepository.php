<?php
namespace App\Repositories\Implementation;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\DTOs\SaveInvoiceItemDTO;
use App\Repositories\IInvoiceItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InvoiceItemRepository implements IInvoiceItemRepository{
    public function createMany(
        Invoice $invoice,
        array $items
    ): void {

        $invoice->items()->createMany(

            collect($items)->map(fn ($item) => [

                'drug_id' => $item->drug_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,

            ])->toArray()

        );
    }
    public function update(InvoiceItem $invoiceItem, SaveInvoiceItemDTO $dto): InvoiceItem
    {
        $invoiceItem->update([
            'drug_id' => $dto->drug_id,
            'quantity' => $dto->quantity,
            'unit_price' => $dto->unit_price,
        ]);
        return $invoiceItem->refresh();
    }
    public function delete(InvoiceItem $invoiceItem): bool
    {
        $invoiceItem->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return InvoiceItem::paginate(25);
    }

    public function findById(int $id): InvoiceItem
    {
        return InvoiceItem::findOrFail($id);
    }

    public function all(): Collection
    {
        return InvoiceItem::all();
    }
}
