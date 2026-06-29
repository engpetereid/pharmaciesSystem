<?php

namespace App\Services\Supervisor;

use App\DTOs\SaveOrderDTO;
use App\Models\Order;

interface IWarehouseService
{


    public function setMinimumQuantity(int $drugId, int $pharmacyId, int $minimum): void;
}
