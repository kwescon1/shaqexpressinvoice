<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentStockLevel = $this->faker->numberBetween(10, 100); // Random current stock level
        $quantityAdded = $this->faker->numberBetween(1, 50); // Random restocked quantity

        return [
            'uuid' => Uuid::uuid4()->toString(), // Generate a UUID for the product
            'branch_id' => null, // Create or associate a branch
            'category_id' => null, // Create or associate a category
            'name' => $this->faker->word(), // Generate a random product name
            'last_updated_restock_level' => $currentStockLevel + $quantityAdded, // Calculate restock level
            'current_stock_level' => $currentStockLevel, // Assign current stock level
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
