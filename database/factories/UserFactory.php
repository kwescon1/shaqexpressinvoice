<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Facility;
use App\Models\User;
use Database\Factories\Concerns\RefreshOnCreate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * @use RefreshOnCreate<User>
     */
    use RefreshOnCreate;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Uuid::uuid4()->toString(), // Generate a UUID for the user
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'role' => fake()->randomElement(['admin', 'salesperson']), // Randomly assign a role
            'facility_id' => Facility::factory(), // Create or associate a facility
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
