<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Pharma;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {
        $query = $this->getFilteredQuery($request);
        $invoices = $query->paginate(20);
        $pharmacies = Pharma::all();
        return view('admin.invoices.index', compact('invoices', 'pharmacies'));
    }


    public function show(int $id)
    {
        $invoice = Invoice::with(['pharmacy', 'items.drug'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    private function getFilteredQuery(Request $request)
    {
        $query = Invoice::with(['pharmacy']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('pharmacy_id')) {
            $query->where('pharmacy_id', $request->pharmacy_id);
        }

        return $query;
    }
}
