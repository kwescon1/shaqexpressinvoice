<?php

declare(strict_types=1);

beforeEach(function () {
    // Run the seeder
    $this->seed(Database\Seeders\AdminSeeder::class);
});

test('admin seeder creates user', function () {
    $this->assertDatabaseHas('users', [
        'email' => 'admin@shaqexpress.com', // The email set in the seeder
    ]);
});
