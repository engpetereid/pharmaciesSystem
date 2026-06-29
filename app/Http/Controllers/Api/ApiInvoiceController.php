<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveInvoiceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use App\Models\Invoice;
use App\Services\Admin\IInvoiceService;
use App\Services\Admin\Implementation\InvoiceService;



class ApiInvoiceController extends Controller
{
    public function __construct(
        protected IInvoiceService $invoiceService,
    ){}
    public function index()
    {
        $invoices = $this->invoiceService->paginate();

        return response()->json([
            'status' => true,
            'data'   => $invoices,
        ], 200);
    }

    public function store(CreateInvoiceRequest $request)
    {
        $dto = SaveInvoiceDTO::fromRequest($request);
        $invoice = $this->invoiceService->store($dto);

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 201);
    }

    public function show(int $id)
    {
        $invoice = $this->invoiceService->findById($id);

        return response()->json([
            'status' => true,
            'data'   => $invoice,
        ], 200);
    }

    public function update(EditInvoiceRequest $request, Invoice $invoice)
    {
        $dto = SaveInvoiceDTO::fromRequest($request);
        $invoice = $this->invoiceService->update($invoice,$dto);

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 200);
    }

    public function destroy(Invoice $invoice)
    {
        $this->invoiceService->delete($invoice);

        return response()->json([
            'status'  => true,
            'message' => 'Invoice deleted successfully',
        ], 200);
    }
}
