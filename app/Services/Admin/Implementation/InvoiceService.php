<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveInvoiceDTO;
use App\DTOs\SaveNotificationDTO;
use App\Models\Invoice;
use App\Repositories\IDrugRepository;
use App\Repositories\IInvoiceItemRepository;
use App\Repositories\IInvoiceRepository;
use App\Repositories\INotificationRepository;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\IInvoiceService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceService implements IInvoiceService
{
    public function __construct(
        protected IInvoiceRepository  $invoiceRepository,
        protected IWarehouseRepository  $warehouseRepository,
        protected IInvoiceItemRepository $invoiceItemRepository,
        protected IDrugRepository $drugRepository,
        protected INotificationRepository $notificationRepository,
    ){}
    public function store(SaveInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($dto) {
            $invoice = $this->invoiceRepository->store($dto);

            $invoice = $this->processInvoiceItems($invoice, $dto);
            return $invoice;
        });
    }


    public function update(Invoice $invoice, SaveInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($invoice, $dto) {
            $invoice->loadMissing('items');
            foreach ($invoice->items as $oldItem) {
                $warehouse = $this->warehouseRepository->findByDrugAndPharmacy($invoice->pharmacy_id,$oldItem->drug_id);
                $this->warehouseRepository->increment($warehouse,'quantity',$oldItem->quantity);
                $this->invoiceItemRepository->delete($oldItem);
            }
            $invoice = $this->processInvoiceItems($invoice, $dto);

            $this->invoiceRepository->update($invoice, $dto);
            return $invoice;

        });
    }

    public function delete(Invoice $invoice): bool
    {
        return DB::transaction(function () use ($invoice) {
            $invoice->loadMissing('items');
            foreach ($invoice->items as $item) {
                $warehouse = $this->warehouseRepository->findByDrugAndPharmacy($invoice->pharmacy_id,$item->drug_id);
                $this->warehouseRepository->increment($warehouse,'quantity',$item->quantity);
                $this->invoiceItemRepository->delete($item);
            }


            return (bool) $this->invoiceRepository->delete($invoice);
        });
    }

    public function findById(int $id): Invoice
    {
        return $this->invoiceRepository->findById($id);
    }

    public function all()
    {
        return $this->invoiceRepository->all();
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->invoiceRepository->paginate();
    }

    private function processInvoiceItems(Invoice $invoice ,saveInvoiceDTO $dto): Invoice
    {
        $total = 0;
        foreach ($dto->items as $item) {

            $drug = $this->drugRepository->findById($item->drug_id);
            $lineTotal = $drug->price * $item->quantity;
            $warehouse = $this->warehouseRepository->findByDrugAndPharmacy($dto->pharmacy_id,$item->drug_id);
            if (! $warehouse || $warehouse->quantity < $item->quantity) {
                throw new \RuntimeException('Not enough stock for drug: ' . $drug->name);
            }
            $this->warehouseRepository->decrement($warehouse,'quantity',$item->quantity);
            $warehouse->refresh();
            if ($warehouse->minimum_quantity !== null && $warehouse->quantity < $warehouse->minimum_quantity) {
                $this->notificationRepository->store( new SaveNotificationDTO(
                    pharmacy_id: $invoice->pharmacy_id,
                    drug_id: $drug->id,
                    message: "Stock below minimum threshold for: {$drug->name}",
                ));

            }
            $total += $lineTotal;
        }
        $this->invoiceItemRepository->createMany($invoice,  $dto->items);
        $this->invoiceRepository->updatePrice($invoice, $total);
        return $invoice;
    }
}
