<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\CreateInvoiceRequest;
use App\Http\Requests\Invoice\EditInvoiceRequest;
use App\Models\Drug;
use App\Models\Invoice;
use App\Services\Supervisor\InvoiceService;

class SupervisorInvoiceController extends Controller
{
    public function index()
    {
        $pharmacy = auth()->user()->pharmacy;

        abort_unless($pharmacy, 403, 'No pharmacy is linked to your account.');

        $invoices = Invoice::with('pharmacy')
            ->where('pharmacy_id', $pharmacy->id)
            ->latest()
            ->paginate(20);

        return view('supervisor.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $drugs = Drug::select('id', 'name', 'price')->orderBy('name')->get();
        return view('supervisor.invoices.create', compact('drugs'));
    }

    public function store(CreateInvoiceRequest $request, InvoiceService $service)
    {
        $service->create($request->validated());
        return redirect()->route('supervisor.invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(int $id)
    {
        $invoice = Invoice::with(['pharmacy', 'items.drug'])
            ->where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        return view('supervisor.invoices.show', compact('invoice'));
    }

    public function edit(int $id)
    {
        $invoice = Invoice::with(['items.drug'])
            ->where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        $drugs = Drug::select('id', 'name', 'price')->orderBy('name')->get();
        return view('supervisor.invoices.edit', compact('invoice', 'drugs'));
    }

    /**
     * Fix Issue 11: load and scope the invoice once (authorization).
     * Pass the model directly to the service — no second DB round-trip.
     */
    public function update(EditInvoiceRequest $request, int $id, InvoiceService $service)
    {
        $invoice = Invoice::where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        $service->update($invoice, $request->validated());

        return redirect()->route('supervisor.invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Fix Issue 11: same single-query pattern as update().
     */
    public function destroy(int $id, InvoiceService $service)
    {
        $invoice = Invoice::where('pharmacy_id', auth()->user()->pharmacy?->id)
            ->findOrFail($id);

        $service->delete($invoice);

        return redirect()->route('supervisor.invoices.index')
            ->with('success', 'Invoice deleted and stock restored.');
    }
}
