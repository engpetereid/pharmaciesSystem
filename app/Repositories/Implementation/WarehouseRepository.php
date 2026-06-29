<?php
namespace App\Repositories\Implementation;
use App\Models\Pharma;
use App\Models\Warehouse;
use App\DTOs\SaveWarehouseDTO;
use App\Repositories\IWarehouseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WarehouseRepository implements IWarehouseRepository{
    public function store(SaveWarehouseDTO $dto): Warehouse
    {
        return Warehouse::create([
            'quantity' => $dto->quantity,
            'pharmacy_id' => $dto->pharmacy_id,
            'drug_id'=>$dto->drug_id,
            'minimum_quantity'=>$dto->minimum_quantity,
        ]);
    }

    public function update(Warehouse $warehouse, SaveWarehouseDTO $dto): Warehouse
    {
        $warehouse->update([
            'quantity' => $dto->quantity,
            'pharmacy_id' => $dto->pharmacy_id,
            'drug_id'=>$dto->drug_id,
            'minimum_quantity'=>$dto->minimum_quantity,
        ]);
        return $warehouse->refresh();
    }

    public function delete(Warehouse $warehouse): bool
    {
        $warehouse->delete();
        return true;
    }

    public function paginate(): LengthAwarePaginator
    {
        return Warehouse::paginate(25);
    }

    public function findById(int $id): Warehouse
    {
        return Warehouse::with('drug')->findOrFail($id);
    }

    public function findByPharma(Pharma $pharma): ?Collection
    {
        return Warehouse::with('drug')->where('pharmacy_id',$pharma->id)->get();
    }

    public function findByDrugAndPharmacy(int $pharmacy_id, int $drug_id): ?Warehouse
    {
        return Warehouse::where('pharmacy_id', $pharmacy_id)
            ->where('drug_id', $drug_id)
            ->first();
    }

    public function all(): Collection
    {
        return Warehouse::all();
    }
    public function firstOrCreate(array $attributes, array $values = []): Warehouse
    {
        return Warehouse::firstOrCreate($attributes, $values);
    }
    public function increment(Warehouse $warehouse, string $attribute, int $value): bool
    {
        return $warehouse->increment($attribute, $value);
    }
    public function decrement(Warehouse $warehouse, string $attribute, int $value): bool
    {
        return $warehouse->decrement($attribute, $value);
    }

    public function updateMinimum(Warehouse $warehouse, int $minimum_quantity): bool
    {
        return $warehouse->update(['minimum_quantity' => $minimum_quantity]);
    }

}
