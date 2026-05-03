<?php

namespace App\Services\Admin;

use App\Models\Category;

/**
 * Fix Issue 9: added PHP return type declarations to all methods.
 * Variable name corrected from $categories (plural) to $category (singular)
 * since we are working with a single model instance.
 */
class CategoryService
{
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id): bool
    {
        return (bool) Category::findOrFail($id)->delete();
    }
}
