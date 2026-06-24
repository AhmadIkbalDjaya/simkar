<?php

namespace Database\Factories;

use App\Enums\TransferReasonType;
use App\Enums\TransferStatus;
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
            'transfer_number' => 'TRF-'.fake()->unique()->numerify('#####'),
            'inmate_id' => Inmate::factory(),
            'room_from_id' => Room::factory(),
            'room_to_id' => Room::factory(),
            'reason' => fake()->randomElement(TransferReasonType::cases()),
            'notes' => fake()->optional(0.7)->sentence(),
            'transferred_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => TransferStatus::Completed,
            'officer_name' => fake()->name(),
            'officer_signature' => null,
            'created_by' => User::factory(),
        ];
    }
}
