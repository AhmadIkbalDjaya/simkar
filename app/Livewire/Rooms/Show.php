<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use App\Models\RoomTransfer;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class Show extends Component
{
    public Room $room;
    public Collection $occupants;
    public \Illuminate\Support\Collection $transfers;

    public function mount(Room $room): void
    {
        $this->room = $room;
        $this->occupants = $room->inmates;
        $this->transfers = RoomTransfer::with(['inmate', 'roomFrom', 'roomTo'])
            ->where('room_from_id', $room->id)
            ->orWhere('room_to_id', $room->id)
            ->latest('transferred_at')
            ->limit(10)
            ->get();
    }

    public function getTitle(): string
    {
        return "{$this->room->name} - SIMKAR";
    }
}
