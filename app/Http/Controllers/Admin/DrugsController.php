<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Drug\CreateDrugRequest;
use App\Http\Requests\Drug\EditDrugRequest;
use App\Models\Category;
use App\Models\Drug;
use App\Services\Admin\Implementation\DrugService;

class DrugsController extends Controller
{
    public function index()
    {
        $drugs = Drug::with('category')->paginate(25);
        return view('admin.drugs.index', compact('drugs'));
    }
    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.drugs.create', compact('categories'));
    }

    public function store(CreateDrugRequest $request, DrugService $service)
    {
        $service->create($request->validated());
        return redirect()->route('admin.drugs.index')
            ->with('success', 'Drug created successfully.');
    }

    public function show(int $id)
    {
        $drug = Drug::with('category')->findOrFail($id);
        return view('admin.drugs.show', compact('drug'));
    }

    public function edit(int $id)
    {
        $drug       = Drug::findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.drugs.edit', compact('categories', 'drug'));
    }

    public function update(EditDrugRequest $request, int $id, DrugService $service)
    {
        $service->update($id, $request->validated());
        return redirect()->route('admin.drugs.index')
            ->with('success', 'Drug updated successfully.');
    }

    public function destroy(int $id, DrugService $service)
    {
        $service->delete($id);
        return redirect()->route('admin.drugs.index')
            ->with('success', 'Drug deleted successfully.');
    }
}
