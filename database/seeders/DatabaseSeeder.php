<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call the UserFacilityBranchSeeder to seed facilities, branches, and users
        $this->call(UserFacilityBranchSeeder::class);
        $this->call(CategoryProductStockSeeder::class);
    }
}
