<?php

namespace App\Repositories;

use App\Models\Category;
use App\DTOs\SaveCategoryDTO;
use Illuminate\Support\Collection;

interface ICategoryRepository
{
    public function store(SaveCategoryDTO $dto): Category;
    public function update(Category $category, SaveCategoryDTO $dto): Category;
    public function delete(Category $category): bool;

    public function paginate();

    public function findById(int $id): Category;

    public function all(): Collection;

}
