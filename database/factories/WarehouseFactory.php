<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Pharma;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
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
            'quantity'=>$this->faker->numberBetween(1,100),
            'minimum_quantity'=>$this->faker->numberBetween(1,100),
        ];
    }
}
