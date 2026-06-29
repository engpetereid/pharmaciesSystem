<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveWarehouseDTO;
use App\Models\Drug;
use App\Models\Pharma;
use App\Models\Warehouse;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\IWarehouseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class WarehouseService implements IWarehouseService
{
    public function __construct(
        protected IWarehouseRepository $warehouseRepository
    ) {}
    public function store(SaveWarehouseDTO $dto): Warehouse
    {
        return $this->warehouseRepository->store($dto);
    }

    public function update(Warehouse $warehouse , SaveWarehouseDTO $dto): Warehouse
    {
        return $this->warehouseRepository->update($warehouse, $dto);
    }

    public function delete(Warehouse $warehouse): bool
    {
        return $this->warehouseRepository->delete($warehouse);
    }
    public function findById(int $id): Warehouse
    {
        return $this->warehouseRepository->findById($id);
    }
    public function findByPharma(Pharma $pharma): Warehouse
    {
        return $this->warehouseRepository->findByPharma($pharma);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->warehouseRepository->paginate();
    }

    public function all()
    {
        return $this->warehouseRepository->all();
    }
    public function findByPharmacyAndDrug(Pharma $pharma , Drug $drug) : Warehouse
    {
        return $this->warehouseRepository->findByDrugAndPharmacy($pharma->id,$drug->id);
    }

    public function getItemsByPharma(Pharma $pharma) : ?Collection
    {
        return $this->warehouseRepository->findByPharma($pharma);
    }

}
