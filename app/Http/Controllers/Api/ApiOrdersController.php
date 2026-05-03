<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\OrderService;

class ApiOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['drug', 'pharmacy'])->paginate(20);

        return response()->json([
            'status' => true,
            'data'   => $orders,
        ], 200);
    }

    public function acceptOrder(int $id, OrderService $service)
    {
        $service->accept($id);

        return response()->json([
            'status'  => true,
            'message' => 'Order accepted and stock updated.',
        ], 200);
    }
}
