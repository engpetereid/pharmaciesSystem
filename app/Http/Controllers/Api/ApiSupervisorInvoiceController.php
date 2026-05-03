<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use App\Models\Invoice;
use App\Services\Supervisor\InvoiceService;

class ApiSupervisorInvoiceController extends Controller
{
    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;

        abort_unless($pharmacy, 403, 'No pharmacy is linked to your account.');

        $invoices = Invoice::with(['pharmacy', 'items.drug'])
            ->where('pharmacy_id', $pharmacy->id)
            ->latest()
            ->paginate(20);

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
        $invoice = Invoice::with(['pharmacy', 'items.drug'])
            ->where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => $invoice,
        ], 200);
    }

    /**
     * Fix Issue 11: single-query authorization; model is passed to service.
     */
    public function update(EditInvoiceRequest $request, int $id, InvoiceService $service)
    {
        $invoice = Invoice::where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        $invoice = $service->update($invoice, $request->validated());

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 200);
    }

    /**
     * Fix Issue 11: single-query authorization; model is passed to service.
     */
    public function destroy(int $id, InvoiceService $service)
    {
        $invoice = Invoice::where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        $service->delete($invoice);

        return response()->json([
            'status'  => true,
            'message' => 'Invoice deleted successfully',
        ], 200);
    }
}
