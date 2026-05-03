<?php

namespace App\Services\Supervisor;

use App\Models\Order;
use App\Models\Warehouse;

class WarehouseService
{

    public function makeOrder(int $pharmacyId, array $data): Order
    {
        return Order::create([
            'pharmacy_id' => $pharmacyId,
            'drug_id'     => $data['drug_id'],
            'quantity'    => $data['quantity'],
            'accepted'    => false,
        ]);
    }

    public function deleteOrder(int $id): bool
    {
        return (bool) Order::destroy($id);
    }

    public function minimum(int $drugId, int $pharmacyId, int $minimum): void
    {
        $warehouse = Warehouse::where('drug_id', $drugId)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

        $warehouse->update(['minimum' => $minimum]);
    }
}
