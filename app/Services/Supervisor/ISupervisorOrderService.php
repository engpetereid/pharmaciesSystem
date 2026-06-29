<?php

namespace App\Services\Supervisor;

use App\DTOs\SaveOrderDTO;
use App\Models\Order;

interface ISupervisorOrderService
{
    public function makeOrder(SaveOrderDTO $dto): Order;


    public function deleteOrder(Order $order): bool;

}
