<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\EditCategoryRequest;
use App\Models\Category;
use App\Services\Admin\CategoryService;

class CategoriesController extends Controller
{
    /**
     * Fix Issue 8: replaced Category::all() with paginate(25) to prevent
     * full-table loads as the dataset grows.
     */
    public function index()
    {
        $categories = Category::withCount('drugs')->paginate(25);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CreateCategoryRequest $request, CategoryService $categoryService)
    {
        $categoryService->create($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(int $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(int $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(EditCategoryRequest $request, int $id, CategoryService $categoryService)
    {
        $categoryService->update($id, $request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(int $id, CategoryService $categoryService)
    {
        $categoryService->delete($id);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
