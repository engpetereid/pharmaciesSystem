<?php

namespace App\Services\Admin;

use App\Models\Pharma;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class PharmaService
{
    public function create(array $data): Pharma
    {
        return Pharma::create($data);
    }

    public function update(int $id, array $data): Pharma
    {
        $pharma = Pharma::findOrFail($id);
        $pharma->update($data);
        return $pharma;
    }

    public function delete(int $id): bool
    {
        $pharma = Pharma::findOrFail($id);
        return (bool) $pharma->delete();
    }

    /**
     * Fix Issue 4 (SQL Injection): replaced DB::raw() with interpolated user
     * values with firstOrCreate() + increment(). The increment() method issues
     * a parameterised UPDATE — zero injection risk regardless of input.
     * The DB::raw('COALESCE(minimum, 0)') no-op is also removed.
     */
    public function addDrugsToPharmacy(array $data): void
    {
        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $item) {
                $warehouse = Warehouse::firstOrCreate(
                    [
                        'pharmacy_id' => $data['pharmacy_id'],
                        'drug_id'     => $item['drug_id'],
                    ],
                    [
                        'quantity' => 0,
                        'minimum'  => 0,
                    ]
                );

                $warehouse->increment('quantity', (int) $item['quantity']);
            }
        });
    }
}
