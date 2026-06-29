<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SavePharmaDTO;
use App\DTOs\SaveWarehouseDTO;
use App\Models\Pharma;
use App\Repositories\IPharmaRepository;
use App\Repositories\Implementation\PharmaRepository;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\IPharmaService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


class PharmaService implements IPharmaService
{
    public function __construct(
        protected IPharmaRepository $PharmaRepository,
        protected IWarehouseRepository $warehouseRepository,
    ) {}
    public function store(SavePharmaDTO $dto): Pharma
    {
        return $this->PharmaRepository->store($dto);
    }

    public function update(Pharma $pharma , SavePharmaDTO $dto): Pharma
    {
        return $this->PharmaRepository->update($pharma, $dto);
    }

    public function delete(Pharma $pharma): bool
    {
        return $this->PharmaRepository->delete($pharma);
    }
    public function findById(int $id): Pharma
    {
        return $this->PharmaRepository->findById($id);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->PharmaRepository->paginate();
    }

    public function all()
    {
        return $this->PharmaRepository->all();
    }

    public function addMultipleDrugsToPharmacy(Pharma $pharma, array $drugIds, int $quantity): bool
    {
        return DB::transaction(function () use ($pharma, $drugIds, $quantity) {
            foreach ($drugIds as $drug_id) {
                $warehouse = $this->warehouseRepository->firstOrCreate(
                    [
                        'pharmacy_id' => $pharma->id,
                        'drug_id'     => $drug_id
                    ],
                    [
                        'quantity' => 0,
                        'minimum_quantity'  => 0,
                    ]
                );

                $this->warehouseRepository->increment($warehouse, 'quantity', $quantity);
            }

            return true;
        });
    }

}
