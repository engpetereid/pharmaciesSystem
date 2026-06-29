<?php

namespace App\Services\Admin;

use App\DTOs\SavePharmaDTO;
use App\Models\Pharma;
use Illuminate\Pagination\LengthAwarePaginator;

interface IPharmaService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Pharma;

    public function store(SavePharmaDTO $dto): Pharma;

    public function update(Pharma $pharma, SavePharmaDTO $dto): Pharma;

    public function delete(Pharma $pharma): bool;

    public function addMultipleDrugsToPharmacy(Pharma $pharma ,  array $drugIds, int $quantity): bool;
}
