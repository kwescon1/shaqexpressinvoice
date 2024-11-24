<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends Factory<Facility>
 */
final class FacilityFactory extends Factory
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
            'name' => fake()->company(),        // Use fake company name
            'code' => mb_strtoupper(fake()->lexify('???')), // Generate a 3-character code
        ];
    }
}
