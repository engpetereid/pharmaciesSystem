<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Drug\CreateDrugRequest;
use App\Http\Requests\Drug\EditDrugRequest;
use App\Models\Category;
use App\Models\Drug;
use App\Services\Admin\DrugService;
use Illuminate\Http\Request;

class ApiDrugsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $drugs=Drug::all();
        return response()->json([
            'status'=>true,
            'data'=>$drugs,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDrugRequest $request ,DrugService  $service )
    {

        $drug= $service->create($request->validated());
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
        $drug=Drug::findOrFail($id);
        return response()->json([
            'status'=>true,
            'data'=>$drug,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditDrugRequest $request, string $id , DrugService  $service)
    {
        //
        $drug = $service->update($id,$request->validated());
        return response()->json([
            'status'=>true,
            'data'=>$drug,
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id ,DrugService  $service)
    {
        $service->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'drug deleted successfully',
        ], 200);

    }
}
