<?php

namespace Database\Factories;

use App\Enums\RoomStatus;
use App\Models\Block;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        $code = 'R-'.fake()->unique()->numerify('###');

        return [
            'block_id' => Block::factory(),
            'code' => $code,
            'name' => 'Kamar '.$code,
            'capacity' => fake()->numberBetween(4, 20),
            'current_occupancy' => 0,
            'status' => RoomStatus::Active,
        ];
    }
}
