<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['drug', 'pharmacy'])->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function acceptOrder(int $id, OrderService $service)
    {
        $service->accept($id);
        return redirect()->route('admin.orders')
            ->with('success', 'Order accepted and stock updated.');
    }
}
