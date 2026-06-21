<?php

namespace Database\Seeders;

use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use LogicException;

class DevSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::query()
            ->where('email', 'admin@simkar.test')
            ->first();

        if ($admin === null) {
            throw new LogicException('Run DatabaseSeeder before DevSeeder to create admin@simkar.test.');
        }

        // Officer users
        $officers = User::factory(5)->officer()->create();

        // Rooms: 4 blocks x 5 rooms each
        $rooms = collect();
        foreach (['A', 'B', 'C', 'D'] as $block) {
            for ($i = 1; $i <= 5; $i++) {
                $rooms->push(Room::factory()->create([
                    'name' => "Kamar {$block}{$i}",
                    'block' => $block,
                ]));
            }
        }

        // Inmates assigned to random rooms
        $inmates = Inmate::factory(50)->create()->each(function (Inmate $inmate) use ($rooms) {
            $room = $rooms->random();

            if ($room->current_occupancy < $room->capacity) {
                $inmate->update(['current_room_id' => $room->id]);
                $room->increment('current_occupancy');
            }
        });

        // Room transfers (historical records)
        $allUsers = $officers->push($admin);

        $inmates->filter(fn ($inmate) => $inmate->current_room_id !== null)->take(30)->each(function (Inmate $inmate) use ($rooms, $allUsers) {
            $transferCount = fake()->numberBetween(1, 3);

            for ($i = 0; $i < $transferCount; $i++) {
                $roomFrom = $rooms->random();
                $roomTo = $rooms->where('id', '!=', $roomFrom->id)->random();
                $user = $allUsers->random();

                RoomTransfer::factory()->create([
                    'inmate_id' => $inmate->id,
                    'room_from_id' => $roomFrom->id,
                    'room_to_id' => $roomTo->id,
                    'officer_name' => $user->name,
                    'created_by' => $user->id,
                ]);
            }
        });
    }
}
