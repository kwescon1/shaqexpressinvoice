<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends Factory<Branch>
 */
final class BranchFactory extends Factory
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
            'facility_id' => null, // Associate with a facility
            'name' => fake()->city(),            // Use fake city name as branch name
            'code' => mb_strtoupper(fake()->lexify('??')), // Generate a 2-character code
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
