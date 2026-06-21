<?php

namespace Database\Seeders;

use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $rooms = [
            'A' => [
                ...$this->numberedRooms('AA', 9),
                ...$this->numberedRooms('AB', 9),
            ],
            'B' => [
                ...$this->numberedRooms('BA', 10),
                ...$this->numberedRooms('BB', 10),
            ],
            'C' => [
                ...$this->numberedRooms('CA', 6),
                ...$this->numberedRooms('CB', 6),
            ],
            'D' => ['Klinik', 'Lansia', 'Rehab'],
        ];

        foreach ($rooms as $block => $roomNames) {
            foreach ($roomNames as $name) {
                Room::query()->updateOrCreate(
                    ['name' => $name],
                    ['block' => $block, 'capacity' => 10],
                );
            }
        }

        // Temporary

        // Officer users
        $officers = User::factory(5)->officer()->create();
        // Inmates assigned to random rooms
        $rooms = Room::query()->get();
        $inmates = Inmate::factory(50)->create()->each(function (Inmate $inmate) use ($rooms) {
            $room = $rooms->random();

            if ($room->current_occupancy < $room->capacity) {
                $inmate->update(['current_room_id' => $room->id]);
                $room->increment('current_occupancy');
            }
        });

        // Room transfers (historical records)
        $allUsers = $officers;

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

    /**
     * @return list<string>
     */
    private function numberedRooms(string $prefix, int $count): array
    {
        return array_map(
            fn (int $number): string => "Kamar {$prefix}{$number}",
            range(1, $count),
        );
    }
}
