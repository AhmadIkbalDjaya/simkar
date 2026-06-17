<?php

namespace Database\Factories;

use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RoomTransfer>
 */
class RoomTransferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'inmate_id' => Inmate::factory(),
            'room_from_id' => Room::factory(),
            'room_to_id' => Room::factory(),
            'transferred_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'officer_name' => fake()->name(),
            'officer_signature' => null,
            'notes' => fake()->optional(0.7)->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
