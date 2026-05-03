<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Drug;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Drug>
 */
class DrugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::factory()->create();
        return [
            //
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'category_id' => $category->id,
        ];
    }
}
