<?php

namespace App\Repositories;

use App\Models\Order;
use App\DTOs\SaveOrderDTO;
use Illuminate\Support\Collection;

interface IOrderRepository
{
    public function store(SaveOrderDTO $dto): Order;
    public function update(Order $order, SaveOrderDTO $dto): Order;
    public function delete(Order $order): bool;

    public function paginate();

    public function findById(int $id): Order;

    public function all(): Collection;

    public function makeAsAccepted(Order $order):bool;

}
