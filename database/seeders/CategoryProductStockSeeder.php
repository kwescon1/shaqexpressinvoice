<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

final class CategoryProductStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Retrieve Existing Branches
        $branches = Branch::all();

        if ($branches->isEmpty()) {
            $this->command->error('No branches found in the database. Please seed branches first.');

            return;
        }

        $this->command->info('Branches retrieved successfully!');

        // Step 2: Create 3 Categories
        $categories = Category::factory()
            ->count(3)
            ->create();

        $this->command->info('Categories seeded successfully!');

        // Step 3: Create Products and Their Stock for Each Category
        $categories->each(function ($category) use ($branches) {
            for ($i = 0; $i < 2; $i++) {

                Product::factory()
                    ->count(50) // Create 50 products per category
                    ->for($category) // Associate each product with the category
                    ->for($branches[$i]) // Associate each product with a random branch
                    ->create()
                    ->each(function ($product) {
                        // Create a single stock record for each product
                        Stock::factory()
                            ->for($product) // Associate the stock with the product
                            ->create();

                        $this->command->info("Stock for product '{$product->name}' in branch '{$product->branch->name}' seeded successfully!");
                    });
            }

        });

        $this->command->info('All categories, products, and stock seeded successfully!');
    }
}
