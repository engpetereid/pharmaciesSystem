<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveDrugDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Drug\CreateDrugRequest;
use App\Http\Requests\Drug\EditDrugRequest;
use App\Models\Drug;
use App\Services\Admin\IDrugService;

class ApiDrugsController extends Controller
{
    public function __construct(
        protected IDrugService $drugService,
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $drugs=$this->drugService->all();
        return response()->json([
            'status'=>true,
            'data'=>$drugs,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDrugRequest $request)
    {
        $dto = SaveDrugDTO::fromRequest($request);
        $drug= $this->drugService->store($dto);
        return response()->json([
            'status'=>true,
            'data'=>$drug,
        ],201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $drug=$this->drugService->findById($id);
        return response()->json([
            'status'=>true,
            'data'=>$drug,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditDrugRequest $request, Drug $drug)
    {
        //
        $dto = SaveDrugDTO::fromRequest($request);
        $drug = $this->drugService->update( $drug,$dto );
        return response()->json([
            'status'=>true,
            'data'=>$drug,
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Drug $drug )
    {
        $this->drugService->delete($drug);
        return response()->json([
            'status' => true,
            'message' => 'drug deleted successfully',
        ], 200);

    }
}
