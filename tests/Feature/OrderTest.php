<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create([
                'role' => 'admin'
            ]),
            'sanctum'
        );
    }

    public function test_orders_index()
    {
        // Arrange
        Order::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/orders');

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => true
            ])
            ->assertJsonCount(3, 'data.data');
    }
    public function test_accept_order()
    {
        // Arrange
        $order = Order::factory()->create();

        // Act
        $response = $this->patchJson("/api/v1/orders/{$order->id}/accept");
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Order accepted and stock updated.',

            ]);

        // Assert database (مهم جدًا)
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'accepted' => 1,
        ]);
    }
}
