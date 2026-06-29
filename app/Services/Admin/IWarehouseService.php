<?php

namespace App\Services\Admin;

use App\DTOs\SaveWarehouseDTO;
use App\Models\Drug;
use App\Models\Pharma;
use App\Models\Warehouse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IWarehouseService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): ?Warehouse;

    public function store(SaveWarehouseDTO $dto): Warehouse;

    public function update(Warehouse $warehouse, SaveWarehouseDTO $dto): Warehouse;

    public function delete(Warehouse $warehouse): bool;

    public function findByPharmacyAndDrug(Pharma $pharma,Drug $drug) : ?Warehouse;

    public function getItemsByPharma(Pharma $pharma) : ?Collection;

}
