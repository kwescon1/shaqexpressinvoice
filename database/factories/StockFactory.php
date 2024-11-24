<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
final class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(), // Generate a unique UUID
            'product_id' => null, // Associate with a product
            'cost_price' => $this->faker->numberBetween(100, 1000), // Random cost price
            'retail_price' => $this->faker->numberBetween(1100, 2000), // Random retail price
            'quantity' => $this->faker->numberBetween(1, 100), // Random quantity
        ];
    }
}
