<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SavePharmaDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pharma\CreatePharmaRequest;
use App\Http\Requests\Pharma\EditPharmaRequest;
use App\Http\Requests\Pharma\StoreDrugsRequest;
use App\Models\Pharma;
use App\Models\Warehouse;
use App\Services\Admin\IDrugService;
use App\Services\Admin\IPharmaService;
use App\Services\Admin\IWarehouseService;

class ApiPharmaciesController extends Controller
{
public function __construct(
    protected IPharmaService $pharmaService,
    protected IWarehouseService $warehouseService,
){}

    public function index()
    {
        $pharmacies = $this->pharmaService->paginate();

        return response()->json([
            'status' => true,
            'data'   => $pharmacies,
        ], 200);
    }

    public function store(CreatePharmaRequest $request)
    {
        $dto= SavePharmaDTO::fromRequest($request);
        $pharma = $this->pharmaService->store($dto);

        return response()->json([
            'status' => true,
            'data'   => $pharma,
        ], 201);
    }


    public function show(int $id)
    {
        $pharmacy = $this->pharmaService->findById($id);
        $items = $this->warehouseService->getItemsByPharma($pharmacy);

        return response()->json([
            'status' => true,
            'data'   => $pharmacy,
            'items'  => $items,
        ], 200);
    }

    public function update(EditPharmaRequest $request,pharma $pharmacy)
    {
        $dto= SavePharmaDTO::fromRequest($request);
        $pharmacy = $this->pharmaService->update($pharmacy,$dto);

        return response()->json([
            'status' => true,
            'data'   => $pharmacy,
        ], 200);
    }

    public function destroy(Pharma $pharmacy)
    {
        $this->pharmaService->delete($pharmacy);

        return response()->json([
            'status'  => true,
            'message' => 'Pharmacy deleted successfully',
        ], 200);
    }

    public function storeDrugs(Pharma $pharma,StoreDrugsRequest $request)
    {
        $data = $request->validated();
        foreach ($request->drug_id as $drug_id)
            $this->pharmaService->addMultipleDrugsToPharmacy($pharma,$drug_id,$data['quantity']);

        return response()->json([
            'status'  => true,
            'message' => 'Drugs added to pharmacy successfully',
        ], 200);
    }
}
