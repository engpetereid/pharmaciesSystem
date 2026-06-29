<?php

namespace App\Services\Admin\Implementation;

use App\DTOs\SaveCategoryDTO;
use App\Models\Category;
use App\Repositories\ICategoryRepository;
use App\Repositories\Implementation\CategoryRepository;
use App\Services\Admin\ICategoryService;
use Illuminate\Pagination\LengthAwarePaginator;


class CategoryService implements ICategoryService
{
    public function __construct(
        protected ICategoryRepository $categoryRepository
    ) {}
    public function store(SaveCategoryDTO $dto): Category
    {
        return $this->categoryRepository->store($dto);
    }

    public function update(Category $category , SaveCategoryDTO $dto): Category
    {
        return $this->categoryRepository->update($category, $dto);
    }

    public function delete(Category $category): bool
    {
        return $this->categoryRepository->delete($category);
    }
    public function findById(int $id): Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate();
    }

    public function all()
    {
        return $this->categoryRepository->all();
    }


}
