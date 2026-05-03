<?php

namespace App\Services\Supervisor;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\StockNotification;
use App\Models\Warehouse;
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

                $warehouse = Warehouse::where('pharmacy_id', $invoice->pharmacy_id)
                    ->where('drug_id', $drug->id)
                    ->first();

                if (! $warehouse || $warehouse->quantity < $item['quantity']) {
                    throw new \RuntimeException('Not enough stock for drug: ' . $drug->name);
                }

                $warehouse->decrement('quantity', $item['quantity']);
                $warehouse->refresh();

                if ($warehouse->minimum !== null && $warehouse->quantity < $warehouse->minimum) {
                    StockNotification::create([
                        'pharmacy_id' => $invoice->pharmacy_id,
                        'drug_id'     => $drug->id,
                        'message'     => 'Stock below minimum threshold for: ' . $drug->name,
                    ]);
                }

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


    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            // Ensure items are loaded.
            $invoice->loadMissing('items');

            // Step 1: restore stock for all old line items.
            foreach ($invoice->items as $oldItem) {
                Warehouse::where('pharmacy_id', $invoice->pharmacy_id)
                    ->where('drug_id', $oldItem->drug_id)
                    ->increment('quantity', $oldItem->quantity);
            }

            // Step 2: delete old line items.
            $invoice->items()->delete();

            // Step 3: apply new items with stock validation.
            $total = 0;

            foreach ($data['items'] as $item) {
                $drug      = Drug::findOrFail($item['drug_id']);
                $unitPrice = $drug->price;
                $lineTotal = $unitPrice * $item['quantity'];

                $warehouse = Warehouse::where('pharmacy_id', $invoice->pharmacy_id)
                    ->where('drug_id', $drug->id)
                    ->first();

                if (! $warehouse || $warehouse->quantity < $item['quantity']) {
                    throw new \RuntimeException('Not enough stock for drug: ' . $drug->name);
                }

                $warehouse->decrement('quantity', $item['quantity']);
                $warehouse->refresh();

                if ($warehouse->minimum !== null && $warehouse->quantity < $warehouse->minimum) {
                    StockNotification::create([
                        'pharmacy_id' => $invoice->pharmacy_id,
                        'drug_id'     => $drug->id,
                        'message'     => 'Stock below minimum threshold for: ' . $drug->name,
                    ]);
                }

                $invoice->items()->create([
                    'drug_id'    => $drug->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'price'      => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $invoice->update([
                'price' => $total,
                'date'  => $data['date'] ?? $invoice->date,
            ]);

            return $invoice;
        });
    }

    /**
     * Fix Issue 11: accepts a pre-loaded, pre-scoped Invoice model instead of
     * an int ID — same reasoning as update().
     */
    public function delete(Invoice $invoice): bool
    {
        return DB::transaction(function () use ($invoice) {
            $invoice->loadMissing('items');

            foreach ($invoice->items as $item) {
                Warehouse::where('pharmacy_id', $invoice->pharmacy_id)
                    ->where('drug_id', $item->drug_id)
                    ->increment('quantity', $item->quantity);
            }

            $invoice->items()->delete();

            return (bool) $invoice->delete();
        });
    }
}
