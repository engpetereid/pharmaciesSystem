<?php
namespace App\Repositories\Implementation;
use App\Models\Order;
use App\DTOs\SaveOrderDTO;
use App\Repositories\IOrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository implements IOrderRepository{
    public function store(SaveOrderDTO $dto): Order
    {
        return Order::create([
            'drug_id'=>$dto->drug_id,
            'pharmacy_id'=>$dto->pharmacy_id,
            'quantity'=>$dto->quantity,
            'accepted'=>$dto->accepted,

        ]);
    }
    public function update(Order $order, SaveOrderDTO $dto): Order
    {
        $order->update([
            'drug_id'=>$dto->drug_id,
            'pharmacy_id'=>$dto->pharmacy_id,
            'quantity'=>$dto->quantity,
            'accepted'=>$dto->accepted,
        ]);
        return $order->refresh();
    }
    public function delete(Order $order): bool
    {
        $order->delete();
        return true;
    }
    public function paginate(): LengthAwarePaginator
    {
        return Order::with(['pharmacy','drug'])->paginate(25);
    }

    public function findById(int $id): Order
    {
        return Order::findOrFail($id);
    }

    public function all(): Collection
    {
        return Order::all();
    }

    public function makeAsAccepted(Order $order): bool
    {
        return $order->update(['accepted' => true]);
    }
}
