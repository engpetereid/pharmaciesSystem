<?php

namespace Database\Factories;

use App\Models\Drug;
use App\Models\Invoice;
use App\Models\Pharma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pharma=Pharma::factory()->create();
        return [
            //
            'pharmacy_id'=>$pharma->id,
            'price' => $this->faker->randomFloat(2,0,100),
            'date' => $this->faker->date(),

        ];
    }
}
