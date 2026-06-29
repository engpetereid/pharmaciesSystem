<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Pharma;
use App\Models\Notification;
use App\Models\User;

class AdminDashboard extends Controller
{
    /**
     * Fix Issue 14: pass real summary metrics to the view instead of returning
     * an empty view that results in blank / hardcoded-zero dashboard cards.
     */
    public function index()
    {
        return view('admin.dashboard', [
            'pharmacyCount'       => Pharma::count(),
            'userCount'           => User::count(),
            'pendingOrderCount'   => Order::where('accepted', false)->count(),
            'todayInvoiceCount'   => Invoice::whereDate('created_at', today())->count(),
            'recentNotifications' => Notification::with(['pharmacy', 'drug'])
                                        ->latest()
                                        ->take(5)
                                        ->get(),
        ]);
    }
}
