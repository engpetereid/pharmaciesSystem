<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Admin\Implementation\OrderService;
use App\Services\Admin\IOrderService;

class ApiOrdersController extends Controller
{
    public function __construct(
        protected IOrderService $orderService,
    ){}
    public function index()
    {
        $orders = $this->orderService->paginate();

        return response()->json([
            'status' => true,
            'data'   => $orders,
        ], 200);
    }

    public function acceptOrder(int $id)
    {
        $this->orderService->accept($id);

        return response()->json([
            'status'  => true,
            'message' => 'Order accepted and stock updated.',
        ], 200);
    }
}
