<?php

namespace App\Services\Admin;

use App\DTOs\SaveCategoryDTO;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICategoryService
{
    public function paginate(): LengthAwarePaginator;

    public function all();

    public function findById(int $id): Category;

    public function store(SaveCategoryDTO $dto): Category;

    public function update(Category $category, SaveCategoryDTO $dto): Category;

    public function delete(Category $category): bool;
}
