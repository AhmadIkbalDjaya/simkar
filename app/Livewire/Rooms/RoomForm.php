<?php

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\Validation\Rule;
use Livewire\Form;

class RoomForm extends Form
{
    public ?int $block_id = null;

    public string $code = '';

    public string $name = '';

    public int $capacity = 1;

    public function rules(): array
    {
        return [
            'block_id' => 'required|exists:blocks,id',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms', 'code')->ignore($this->component->room ?? null),
            ],
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
        ];
    }

    public function setRoom(Room $room): void
    {
        $this->block_id = $room->block_id;
        $this->code = $room->code;
        $this->name = $room->name ?? '';
        $this->capacity = $room->capacity;
    }

    public function store(): Room
    {
        $this->validate();

        return Room::create($this->only(['block_id', 'code', 'name', 'capacity']));
    }

    public function update(Room $room): void
    {
        $this->validate();

        $room->update($this->only(['block_id', 'code', 'name', 'capacity']));
    }
}
