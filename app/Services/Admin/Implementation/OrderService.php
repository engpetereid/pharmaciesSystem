<?php

namespace App\Services\Admin\Implementation;


use App\Repositories\IOrderRepository;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\IOrderService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderService implements IOrderService
{
    public function __construct(
        protected IOrderRepository $orderRepository,
        protected IWarehouseRepository $warehouseRepository
    ) {}
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $order = $this->orderRepository->findById($id);
            return $this->orderRepository->delete($order);
        });
    }


    public function accept(int $id): bool
    {
        return DB::transaction(function () use ($id) {

            $order = $this->orderRepository->findById($id);
            if ($order->accepted) {
                return false;
            }

            $warehouse = $this->warehouseRepository->firstOrCreate([
                'pharmacy_id' => $order->pharmacy_id,
                'drug_id'     => $order->drug_id,
            ]);
            $this->warehouseRepository->increment($warehouse,'quantity',$order->quantity);
            $this->orderRepository->makeAsAccepted($order);
            return true;
        });
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->orderRepository->paginate();
    }
}
