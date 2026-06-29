<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Models\Order;
use App\Models\Warehouse;
use App\Services\Supervisor\implemantation\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $pharmacyId = auth()->user()->pharmacy?->id;

        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        $items = Warehouse::with('drug')
            ->where('pharmacy_id', $pharmacyId)
            ->get();

        $orders = Order::with('drug')
            ->where('pharmacy_id', $pharmacyId)
            ->get();

        return view('supervisor.warehouse.index', compact('items', 'orders'));
    }

    public function require()
    {
        $drugs = Drug::select('id', 'name', 'price')->orderBy('name')->get();
        return view('supervisor.warehouse.require', compact('drugs'));
    }

    public function makeOrder(Request $request, WarehouseService $service): RedirectResponse
    {
        $validated = $request->validate([
            'drug_id'  => ['required', 'integer', 'exists:drugs,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $pharmacyId = auth()->user()->pharmacy?->id;
        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        $service->makeOrder($pharmacyId, $validated);

        return redirect()->route('supervisor.warehouses')
            ->with('success', 'Order placed successfully.');
    }

    /**
     * Fix Issue 3 (IDOR): scope the order to the authenticated supervisor's
     * pharmacy before deletion. Prevents one supervisor from cancelling
     * another pharmacy's orders by guessing an order ID.
     */
    public function deleteOrder(int $id, WarehouseService $service): RedirectResponse
    {
        $pharmacyId = auth()->user()->pharmacy?->id;
        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        // Throws 404 if the order doesn't belong to this supervisor's pharmacy.
        $order = Order::where('id', $id)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

        $service->deleteOrder($order->id);

        return redirect()->route('supervisor.warehouses')
            ->with('success', 'Order cancelled.');
    }

    public function show(int $id)
    {
        $pharmacyId = auth()->user()->pharmacy?->id;
        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        $drug      = Drug::findOrFail($id);
        $warehouse = Warehouse::where('drug_id', $id)
            ->where('pharmacy_id', $pharmacyId)
            ->firstOrFail();

        return view('supervisor.warehouse.minimum', compact('drug', 'warehouse'));
    }


    public function minimum(Request $request, WarehouseService $service): RedirectResponse
    {
        $pharmacyId = auth()->user()->pharmacy?->id;
        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        $validated = $request->validate([
            'drug_id' => ['required', 'integer', 'exists:drugs,id'],
            'minimum' => ['required', 'integer', 'min:0'],
        ]);

        // Ensure the warehouse row belongs to this supervisor's pharmacy.
        Warehouse::where('pharmacy_id', $pharmacyId)
            ->where('drug_id', $validated['drug_id'])
            ->firstOrFail();

        $service->minimum($validated['drug_id'], $pharmacyId, $validated['minimum']);

        return redirect()->route('supervisor.warehouses')
            ->with('success', 'Minimum threshold updated.');
    }
}
