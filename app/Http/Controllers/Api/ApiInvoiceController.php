<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use App\Models\Invoice;
use App\Services\Admin\InvoiceService;  // Fix: was incorrectly importing Supervisor\InvoiceService.
                                        // The admin API manages all invoices without pharmacy scoping,
                                        // so it must use the Admin service, not the Supervisor one.

class ApiInvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['pharmacy', 'items.drug'])->paginate(20);

        return response()->json([
            'status' => true,
            'data'   => $invoices,
        ], 200);
    }

    public function store(CreateInvoiceRequest $request, InvoiceService $service)
    {
        $invoice = $service->create($request->validated());

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 201);
    }

    public function show(int $id)
    {
        $invoice = Invoice::with(['pharmacy', 'items.drug'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => $invoice,
        ], 200);
    }

    public function update(EditInvoiceRequest $request, int $id, InvoiceService $service)
    {
        $invoice = $service->update($id, $request->validated());

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 200);
    }

    public function destroy(int $id, InvoiceService $service)
    {
        $service->delete($id);

        return response()->json([
            'status'  => true,
            'message' => 'Invoice deleted successfully',
        ], 200);
    }
}
