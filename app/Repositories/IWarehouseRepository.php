<?php

namespace App\Repositories;

use App\Models\Pharma;
use App\Models\Warehouse;
use App\DTOs\SaveWarehouseDTO;
use Illuminate\Support\Collection;

interface IWarehouseRepository
{
    public function store(SaveWarehouseDTO $dto): Warehouse;

    public function update(Warehouse $warehouse, SaveWarehouseDTO $dto): Warehouse;

    public function delete(Warehouse $warehouse): bool;

    public function paginate();

    public function findById(int $id): Warehouse;

    public function findByDrugAndPharmacy(int $pharmacy_id, int $drug_id): ?Warehouse;

    public function all(): Collection;

    public function firstOrCreate(array $attributes, array $values = []): Warehouse;

    public function increment(Warehouse $warehouse, string $attribute, int $value): bool;
    public function decrement(Warehouse $warehouse, string $attribute, int $value): bool;

    public function updateMinimum(Warehouse $warehouse, int $minimum_quantity): bool;

    public function findByPharma(Pharma $pharma): ?Collection;

}
