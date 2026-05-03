<?php

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);
            return (bool) $order->delete();
        });
    }


    public function accept(int $id): void
    {
        DB::transaction(function () use ($id) {

            $order = Order::findOrFail($id);

            if ($order->accepted) {
                return;
            }

            $warehouse = Warehouse::firstOrCreate([
                'pharmacy_id' => $order->pharmacy_id,
                'drug_id'     => $order->drug_id,
            ]);

            $warehouse->increment('quantity', $order->quantity);

            $order->update(['accepted' => true]);
        });
    }
}
