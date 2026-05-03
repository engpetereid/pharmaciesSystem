<?php

namespace App\Services\Admin;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceService
{

    public function create(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = Invoice::create([
                'date'        => $data['date'],
                'pharmacy_id' => $data['pharmacy_id'],
                'price'       => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $drug      = Drug::findOrFail($item['drug_id']);
                $unitPrice = $drug->price;
                $lineTotal = $unitPrice * $item['quantity'];

                $invoice->items()->create([
                    'drug_id'    => $drug->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'price'      => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $invoice->update(['price' => $total]);

            return $invoice;
        });
    }


    public function update(int $id, array $data): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        return $invoice;
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $invoice = Invoice::findOrFail($id);
            $invoice->items()->delete();
            return (bool) $invoice->delete();
        });
    }
}
