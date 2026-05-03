<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class ApiCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'status' => true,
            'data' => Category::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request ,CategoryService $categoryService )
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name'
        ]);
        $category = $categoryService->create($validatedData);

        return response()->json([
            'status' => true,
            'data' => $category,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category=Category::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id, CategoryService $categoryService)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id
        ]);
        $category = $categoryService->update($id, $validatedData);

        return response()->json([
            'status' => true,
            'data' => $category,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id,CategoryService $categoryService)
    {
        //
        $categoryService->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);

    }
}
