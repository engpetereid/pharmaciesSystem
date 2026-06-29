<?php

namespace App\Http\Controllers\Api;

use App\DTOs\SaveInvoiceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use App\Models\Invoice;
use App\Services\Supervisor\IInvoiceService;
class ApiSupervisorInvoiceController extends Controller
{

    public function __construct(
        protected IInvoiceService  $invoiceService,
    ){}
    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;

        abort_unless($pharmacy, 403, 'No pharmacy is linked to your account.');

        $invoices = $this->invoiceService->supervisorInvoices($pharmacy->id);
        return response()->json([
            'status' => true,
            'data'   => $invoices,
        ], 200);
    }

    public function store(CreateInvoiceRequest $request)
    {
        $dto = SaveInvoiceDto::fromRequest($request);
        $invoice = $this->invoiceService->store($dto);

        return response()->json([
            'status' => true,
            'data'   => $invoice->load(['pharmacy', 'items.drug']),
        ], 201);
    }

    public function show(Invoice $invoice)
    {
        $invoice = $this->invoiceService->supervisorInvoices($invoice->id);

        return response()->json([
            'status' => true,
            'data'   => $invoice,
        ], 200);
    }

    /**
     * Fix Issue 11: single-query authorization; model is passed to service.
     */
    public function update(EditInvoiceRequest $request, Invoice $invoice)
    {
        //todo edit
        $invoice = $this->invoiceService->findById($invoice->id);
        $dto = SaveInvoiceDto::fromRequest($request);
        $invoice = $this->invoiceService->update($invoice, $dto);

        return response()->json([
            'status' => true,
            'data'   => $invoice,
        ], 200);
    }

    /**
     * Fix Issue 11: single-query authorization; model is passed to service.
     */
    public function destroy(int $id)
    {

        //todo edit, throw exception if invoec not found, at controller handle exceptio
        //and create custom
        $invoice = $this->invoiceService->findById($id);

        $this->invoiceService->delete($invoice);

        return response()->json([
            'status'  => true,
            'message' => 'Invoice deleted successfully',
        ], 200);
    }
}
