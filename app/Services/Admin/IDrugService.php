<?php

namespace App\Services\Admin;

use App\DTOs\SaveDrugDTO;
use App\Models\Drug;
use Illuminate\Pagination\LengthAwarePaginator;

interface IDrugService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Drug;

    public function store(SaveDrugDTO $dto): Drug;

    public function update(Drug $drug, SaveDrugDTO $dto): Drug;

    public function delete(Drug $drug): bool;
}
