<?php

namespace App\Repositories;

use App\Models\Pharma;
use App\DTOs\SavePharmaDTO;
use Illuminate\Support\Collection;

interface IPharmaRepository
{
    public function store(SavePharmaDTO $dto): Pharma;

    public function update(Pharma $pharma, SavePharmaDTO $dto): Pharma;

    public function delete(Pharma $pharma): bool;

    public function paginate();

    public function findById(int $id): Pharma;
    public function all(): Collection;

}
