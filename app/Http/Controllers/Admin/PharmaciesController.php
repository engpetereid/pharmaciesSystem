<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharma\CreatePharmaRequest;
use App\Http\Requests\Pharma\EditPharmaRequest;
use App\Http\Requests\Pharma\StoreDrugsRequest;
use App\Models\Drug;
use App\Models\Pharma;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\Admin\Implementation\PharmaService;

class PharmaciesController extends Controller
{
    public function index()
    {
        $pharmacies = Pharma::paginate(10);
        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function create()
    {
        $supervisors = User::where('role', 'supervisor')->get();
        return view('admin.pharmacies.create', compact('supervisors'));
    }

    public function store(CreatePharmaRequest $request, PharmaService $pharmaService)
    {
        $pharmaService->create($request->validated());
        return redirect()->route('admin.pharmacies.index')
            ->with('success', 'Pharmacy created successfully.');
    }

    public function show(int $id)
    {
        $pharmacy = Pharma::findOrFail($id);
        $items    = Warehouse::with('drug')
            ->where('pharmacy_id', $id)
            ->get();

        return view('admin.pharmacies.show', compact('pharmacy', 'items'));
    }

    public function edit(int $id)
    {
        $supervisors = User::where('role', 'supervisor')->get();
        $pharmacy    = Pharma::findOrFail($id);
        return view('admin.pharmacies.edit', compact('pharmacy', 'supervisors'));
    }

    public function update(EditPharmaRequest $request, int $id, PharmaService $pharmaService)
    {
        $pharmaService->update($id, $request->validated());
        return redirect()->route('admin.pharmacies.index')
            ->with('success', 'Pharmacy updated successfully.');
    }

    public function destroy(int $id, PharmaService $pharmaService)
    {
        $pharmaService->delete($id);
        return redirect()->route('admin.pharmacies.index')
            ->with('success', 'Pharmacy deleted successfully.');
    }

    public function add(int $id)
    {
        $pharmacy = Pharma::findOrFail($id);
        $drugs    = Drug::all();
        return view('admin.pharmacies.add', compact('drugs', 'pharmacy'));
    }

    public function storeDrugs(StoreDrugsRequest $request, PharmaService $service)
    {
        $validated = $request->validated();
        $service->addDrugsToPharmacy($validated);

        return redirect()->route('admin.pharmacies.show', $validated['pharmacy_id'])
            ->with('success', 'Drugs added to pharmacy successfully.');
    }
}
