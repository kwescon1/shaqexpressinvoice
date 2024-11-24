<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use function Illuminate\Support\enum_value;

final class UserFacilityBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Step 1: Create a Facility and its Branches
        $facility = Facility::factory()
            ->hasBranches(2, function (array $attributes, Facility $facility) {
                return [
                    'name' => "{$facility->name} - Branch ".mt_rand(2, 10),
                ];
            })
            ->create([
                'name' => 'Main Facility',
                'code' => 'FAC',
            ]);

        $this->command->info('Facility and its branches seeded successfully!');

        // Use `whereBelongsTo` to retrieve branches for the facility
        $branches = Branch::whereBelongsTo($facility)->get();

        $this->command->info('First branch updated to head branch successfully!');

        // Step 2: Create an Admin User (not associated with a branch)

        $this->command->info('Admin user seeded successfully!');

        // Step 3: Create Salespersons and associate them with branches
        $salesperson1 = $facility->users()->create([
            'name' => 'Salesperson One',
            'email' => 'sales1@example.com',
            'password' => Hash::make('password'),
            'role' => enum_value(UserRole::SALESPERSON),
            'email_verified_at' => now(),
            'branch_id' => collect($branches)->first()?->getKey(),
        ]);

        $salesperson2 = $facility->users()->create([
            'name' => 'Salesperson Two',
            'email' => 'sales2@example.com',
            'password' => Hash::make('password'),
            'role' => enum_value(UserRole::SALESPERSON),
            'email_verified_at' => now(),
            'branch_id' => collect($branches)->last()?->getKey(),
        ]);

        $this->command->info('Salespersons seeded successfully!');
    }
}
