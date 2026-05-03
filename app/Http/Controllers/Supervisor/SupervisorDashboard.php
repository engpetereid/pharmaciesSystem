<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Warehouse;

class SupervisorDashboard extends Controller
{
    /**
     * Fix Issue 14: pass real per-pharmacy metrics to the view instead of an
     * empty view that renders blank / hardcoded-zero dashboard cards.
     */
    public function index()
    {
        $pharmacyId = auth()->user()->pharmacy?->id;
        abort_unless($pharmacyId, 403, 'No pharmacy linked to your account.');

        return view('supervisor.dashboard', [
            'stockCount'    => Warehouse::where('pharmacy_id', $pharmacyId)->count(),
            'lowStockCount' => Warehouse::where('pharmacy_id', $pharmacyId)
                                  ->whereColumn('quantity', '<', 'minimum')
                                  ->count(),
            'pendingOrders' => Order::where('pharmacy_id', $pharmacyId)
                                  ->where('accepted', false)
                                  ->count(),
            'todayInvoices' => Invoice::where('pharmacy_id', $pharmacyId)
                                  ->whereDate('created_at', today())
                                  ->count(),
        ]);
    }
}
