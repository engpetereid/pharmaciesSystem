<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\SaveCategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\EditCategoryRequest;
use App\Models\Category;
use App\Services\Admin\ICategoryService;
use App\Services\Admin\Implementation\CategoryService;

class CategoriesController extends Controller
{
    protected ICategoryService $categoryService;

    public function __construct(ICategoryService $categoryService){
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->paginate();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        $dto = SaveCategoryDTO::fromRequest($request);
        $this->categoryService->store($dto);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(int $id)
    {
        $category = $this->categoryService->findById($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(int $id)
    {
        $category = $this->categoryService->findById($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(EditCategoryRequest $request, Category $category)
    {
        $dto = SaveCategoryDTO::fromRequest($request);

        $this->categoryService->update($category, $dto);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryService->delete($category);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
