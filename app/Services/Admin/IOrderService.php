<?php

namespace App\Services\Admin;

use App\DTOs\SaveOrderDTO;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface IOrderService
{
    public function accept(int $id): bool;
    public function delete(int $id): bool;

    public function paginate(): LengthAwarePaginator;
}
