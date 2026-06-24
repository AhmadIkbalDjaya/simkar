<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Inmate;
use App\Models\Room;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use JsonException;

class ProdSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function (): void {
            $blockIds = $this->seedBlocks();
            $roomIds = $this->seedRooms($blockIds);

            $this->seedInmates($roomIds);
            $this->syncRoomOccupancy();
        });
    }

    /**
     * @return array<int, int>
     *
     * @throws JsonException
     */
    private function seedBlocks(): array
    {
        $blockIds = [];

        foreach ($this->readJson('blocks.json') as $block) {
            $blockModel = Block::query()->updateOrCreate(
                ['code' => $block['code']],
                [
                    'name' => $block['name'],
                    'status' => $block['status'],
                ],
            );

            $blockIds[$block['id']] = $blockModel->id;
        }

        return $blockIds;
    }

    /**
     * @param  array<int, int>  $blockIds
     * @return array<string, int>
     *
     * @throws JsonException
     */
    private function seedRooms(array $blockIds): array
    {
        $roomIds = [];

        foreach ($this->readJson('rooms.json') as $room) {
            $roomModel = Room::query()->updateOrCreate(
                ['code' => $room['code']],
                [
                    'block_id' => $blockIds[$room['block_id']],
                    'name' => $room['name'] ?? "Kamar {$room['code']}",
                    'capacity' => $room['capacity'],
                    'current_occupancy' => 0,
                    'status' => $room['status'],
                ],
            );

            $roomIds[$room['code']] = $roomModel->id;
        }

        return $roomIds;
    }

    /**
     * @param  array<string, int>  $roomIds
     *
     * @throws JsonException
     */
    private function seedInmates(array $roomIds): void
    {
        foreach ($this->readJson('inmates.json') as $inmate) {
            Inmate::query()->updateOrCreate(
                ['registration_number' => $inmate['registration_number']],
                [
                    'name' => $inmate['name'],
                    'crime_type' => $inmate['crime_type'] ?? null,
                    'admission_date' => $this->parseDate($inmate['admission_date'] ?? null),
                    'placement_date' => $this->parseDate($inmate['placement_date'] ?? null),
                    'expiration_date' => $this->parseDate($inmate['expiration_date'] ?? null),
                    'current_room_id' => $this->roomIdForCode($inmate['current_room_code'] ?? null, $roomIds),
                    'status' => $inmate['status'],
                    'gender' => $inmate['gender'] ?? null,
                ],
            );
        }
    }

    private function syncRoomOccupancy(): void
    {
        Room::query()->update(['current_occupancy' => 0]);

        DB::table('inmates')
            ->select('current_room_id', DB::raw('count(*) as aggregate'))
            ->whereNotNull('current_room_id')
            ->whereNull('deleted_at')
            ->groupBy('current_room_id')
            ->get()
            ->each(function (object $roomOccupancy): void {
                Room::query()
                    ->whereKey($roomOccupancy->current_room_id)
                    ->update(['current_occupancy' => $roomOccupancy->aggregate]);
            });
    }

    private function parseDate(?string $date): ?CarbonImmutable
    {
        if ($date === null || $date === '') {
            return null;
        }

        return CarbonImmutable::createFromFormat('d/m/Y', $date)->startOfDay();
    }

    /**
     * @param  array<string, int>  $roomIds
     */
    private function roomIdForCode(?string $code, array $roomIds): ?int
    {
        if ($code === null || trim($code) === '') {
            return null;
        }

        $code = trim($code);

        if (isset($roomIds[$code])) {
            return $roomIds[$code];
        }

        $codeWithoutNote = preg_replace('/\s*\([^)]*\)\s*$/', '', $code);

        return is_string($codeWithoutNote) ? ($roomIds[$codeWithoutNote] ?? null) : null;
    }

    /**
     * @return list<array<string, mixed>>
     *
     * @throws JsonException
     */
    private function readJson(string $file): array
    {
        return json_decode(
            File::get(database_path("seeders/data/{$file}")),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}
