<?php

namespace App\Livewire\Wbps;

use App\Models\Inmate;
use App\Models\Room;
use Illuminate\Validation\Rule;
use Livewire\Form;

class WbpForm extends Form
{
    public string $registration_number = '';

    public string $name = '';

    public string $gender = '';

    public ?int $current_room_id = null;

    public function rules(): array
    {
        return [
            'registration_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('inmates', 'registration_number')->ignore($this->component->wbp ?? null),
            ],
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'current_room_id' => 'nullable|exists:rooms,id',
        ];
    }

    public function setInmate(Inmate $inmate): void
    {
        $this->registration_number = $inmate->registration_number;
        $this->name = $inmate->name;
        $this->gender = $inmate->gender->value;
        $this->current_room_id = $inmate->current_room_id;
    }

    public function store(): Inmate
    {
        $this->validate();

        if ($this->current_room_id) {
            $room = Room::findOrFail($this->current_room_id);
            abort_if($room->current_occupancy >= $room->capacity, 422, 'Kamar sudah penuh.');
        }

        $inmate = Inmate::create($this->only(['registration_number', 'name', 'gender', 'current_room_id']));

        if ($this->current_room_id) {
            Room::where('id', $this->current_room_id)->increment('current_occupancy');
        }

        return $inmate;
    }

    public function update(Inmate $inmate): void
    {
        $this->validate();

        $oldRoomId = $inmate->current_room_id;
        $newRoomId = $this->current_room_id;

        if ($newRoomId && $newRoomId !== $oldRoomId) {
            $room = Room::findOrFail($newRoomId);
            abort_if($room->current_occupancy >= $room->capacity, 422, 'Kamar sudah penuh.');
        }

        $inmate->update($this->only(['registration_number', 'name', 'gender', 'current_room_id']));

        if ($oldRoomId !== $newRoomId) {
            if ($oldRoomId) {
                Room::where('id', $oldRoomId)->decrement('current_occupancy');
            }
            if ($newRoomId) {
                Room::where('id', $newRoomId)->increment('current_occupancy');
            }
        }
    }
}
