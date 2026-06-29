<?php

namespace App\Services\Supervisor\Implementation;

use App\DTOs\SaveOrderDTO;
use App\Models\Order;
use App\Repositories\IOrderRepository;
use App\Services\Supervisor\ISupervisorOrderService;

class SupervisorOrderService implements ISupervisorOrderService
{
    public function __construct(
        protected IOrderRepository $orderRepository,
    ){}
    public function makeOrder(SaveOrderDTO $dto): Order
    {
        return $this->orderRepository->store($dto);
    }

    public function deleteOrder(Order $order): bool
    {
        return $this->orderRepository->delete($order);
    }

}
