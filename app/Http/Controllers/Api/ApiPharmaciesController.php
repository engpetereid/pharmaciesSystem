<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharma\CreatePharmaRequest;
use App\Http\Requests\Pharma\EditPharmaRequest;
use App\Http\Requests\Pharma\StoreDrugsRequest;
use App\Models\Pharma;
use App\Models\Warehouse;
use App\Services\Admin\PharmaService;

class ApiPharmaciesController extends Controller
{

    public function index()
    {
        $pharmacies = Pharma::paginate(20);

        return response()->json([
            'status' => true,
            'data'   => $pharmacies,
        ], 200);
    }

    public function store(CreatePharmaRequest $request, PharmaService $pharmaService)
    {
        $pharma = $pharmaService->create($request->validated());

        return response()->json([
            'status' => true,
            'data'   => $pharma,
        ], 201);
    }


    public function show(int $id)
    {
        $pharmacy = Pharma::findOrFail($id);
        $items    = Warehouse::with('drug')
            ->where('pharmacy_id', $id)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $pharmacy,
            'items'  => $items,
        ], 200);
    }

    public function update(EditPharmaRequest $request, int $id, PharmaService $pharmaService)
    {
        $pharma = $pharmaService->update($id, $request->validated());

        return response()->json([
            'status' => true,
            'data'   => $pharma,
        ], 200);
    }

    public function destroy(int $id, PharmaService $pharmaService)
    {
        $pharmaService->delete($id);

        return response()->json([
            'status'  => true,
            'message' => 'Pharmacy deleted successfully',
        ], 200);
    }

    public function storeDrugs(StoreDrugsRequest $request, PharmaService $service)
    {
        $service->addDrugsToPharmacy($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Drugs added to pharmacy successfully',
        ], 200);
    }
}
