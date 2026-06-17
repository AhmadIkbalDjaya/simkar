<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Kamar '.fake()->unique()->numerify('###'),
            'block' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'capacity' => fake()->numberBetween(4, 20),
            'current_occupancy' => 0,
        ];
    }
}
