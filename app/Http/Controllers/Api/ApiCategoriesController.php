<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveCategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\SaveNotificationRequest;
use App\Http\Requests\Category\EditCategoryRequest;
use App\Models\Category;
use App\Services\Admin\ICategoryService;
use App\Services\Admin\Implementation\CategoryService;
use Illuminate\Http\Request;

class ApiCategoriesController extends Controller
{
    protected $categoryService;

    public function __construct(ICategoryService $categoryService){
        $this->categoryService = $categoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json([
            'status' => true,
            'data' => $this->categoryService->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        //
        $dto=SaveCategoryDTO::fromRequest($request);
        $category=$this->categoryService->store($dto);
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
        $category = $this -> categoryService->findById($id);
        return response()->json([
            'status' => true,
            'data' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditCategoryRequest $request, Category $category)
    {
        //
        $dto=SaveCategoryDTO::fromRequest($request);
        $updatedCategory=$this->categoryService->update($category, $dto);

        return response()->json([
            'status' => true,
            'data' => $updatedCategory,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $this->categoryService->delete($category);
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);

    }
}
