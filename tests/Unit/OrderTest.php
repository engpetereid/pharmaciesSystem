<?php

namespace Tests\Unit;

use App\Models\Drug;
use App\Models\Order;
use App\Models\Pharma;
use App\Models\Warehouse;
use App\Services\Admin\Implementation\OrderService;
use App\Services\Admin\IOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    use RefreshDatabase;

    public function test_accept_order(){
        $pharma=Pharma::factory()->create();
        $drug=Drug::factory()->create();
        $order = Order::factory()->create([
            'pharmacy_id'=>$pharma->id,
            'drug_id'=>$drug->id,
            'quantity'=>50,
        ]);
        Warehouse::factory()->create([
            'pharmacy_id'=>$pharma->id,
            'drug_id'=>$drug->id,
            'quantity'=>50,
        ]);
        $service = app(IOrderService::class);
        $service->accept($order->id);
        $this->assertDatabaseHas('warehouses',[
            'pharmacy_id'=>$pharma->id,
            'drug_id' => $drug->id,
            'quantity'=>100,

        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'accepted' => 1,
        ]);

    }
    public function test_delete_order()
    {
        $pharma=Pharma::factory()->create();
        $drug=Drug::factory()->create();
        $order = Order::factory()->create([
            'pharmacy_id'=>$pharma->id,
            'drug_id'=>$drug->id,
            'quantity'=>50,
        ]);
        $service = app(IOrderService::class);
        $service->delete($order->id);
        $this->assertDatabaseMissing('orders',[
            'id'=>$order->id,
        ]);
    }

}
