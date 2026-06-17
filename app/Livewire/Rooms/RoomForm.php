<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $block = '';

    #[Validate('required|integer|min:1')]
    public int $capacity = 1;

    public function setRoom(Room $room): void
    {
        $this->name = $room->name;
        $this->block = $room->block ?? '';
        $this->capacity = $room->capacity;
    }

    public function store(): Room
    {
        $this->validate();

        return Room::create($this->only(['name', 'block', 'capacity']));
    }

    public function update(Room $room): void
    {
        $this->validate();

        $room->update($this->only(['name', 'block', 'capacity']));
    }
}
