<?php

namespace App\Services\Supervisor\Implementation;


use App\Repositories\IWarehouseRepository;
use App\Services\Supervisor\IWarehouseService;

class WarehouseService implements IWarehouseService
{
    public function __construct(
        protected IWarehouseRepository $warehouseRepository,
    ){}

    public function setMinimumQuantity(int $drugId, int $pharmacyId, int $minimum): void
    {
        $warehouse = $this->warehouseRepository->findByDrugAndPharmacy($pharmacyId,$drugId);
        if (!$warehouse) {
            throw new \RuntimeException('Warehouse record not found for this drug and pharmacy.');
        }
        $this->warehouseRepository->updateMinimum($warehouse,$minimum);
    }
}
