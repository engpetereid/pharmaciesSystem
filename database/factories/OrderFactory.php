<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Order;
use App\Models\Pharma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'pharmacy_id'=>Pharma::factory()->create(),
            'drug_id'=>Drug::factory()->create(),
            'quantity' => $this->faker->randomFloat(2,0,100),
        ];
    }
}
