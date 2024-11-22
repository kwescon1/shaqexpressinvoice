<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve admin details from environment variables or use default values
        $adminPassword = config('admin.password');
        $adminEmail = config('admin.email');
        $adminName = config('admin.name');

        // Create or update the admin user
        User::firstOrCreate(
            ['email' => $adminEmail], // Match by email
            [
                'name' => $adminName,
                'password' => Hash::make($adminPassword), // Hash the password
                'email_verified_at' => Carbon::now(),
                'role' => 'admin', // Ensure the admin role is assigned
            ]
        );

        $this->command->info('Admin user seeded successfully!');
    }
}
