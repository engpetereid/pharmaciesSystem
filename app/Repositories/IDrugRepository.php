<?php

namespace App\Repositories;

use App\Models\Drug;
use App\DTOs\SaveDrugDTO;
use Illuminate\Support\Collection;

interface IDrugRepository
{
    public function store(SaveDrugDTO $dto): Drug;

    public function update(Drug $Drug, SaveDrugDTO $dto): Drug;

    public function delete(Drug $Drug): bool;

    public function paginate();

    public function findById(int $id): Drug;
    public function all(): Collection;

}
