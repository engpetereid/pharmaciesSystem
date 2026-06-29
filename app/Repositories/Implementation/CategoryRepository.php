<?php
namespace App\Repositories\Implementation;
use App\Models\Category;
use App\DTOs\SaveCategoryDTO;
use App\Repositories\ICategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements ICategoryRepository{
    public function store(SaveCategoryDTO $dto): Category
    {
        return Category::create([
            'name' => $dto->name
        ]);
    }
    public function update(Category $category, SaveCategoryDTO $dto): Category
    {
        $category->update([
            'name'=> $dto->name
        ]);
        return $category->refresh();
    }
    public function delete(Category $category): bool
    {
        $category->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Category::withCount('drugs')
            ->paginate(25);
    }

    public function findById(int $id): Category
    {
        return Category::findOrFail($id);
    }

    public function all(): Collection
    {
        return Category::all();
    }
}
