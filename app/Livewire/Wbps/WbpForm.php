<?php

namespace App\Livewire\Wbps;

use App\Enums\InmateStatus;
use App\Models\Inmate;
use App\Models\Room;
use Illuminate\Validation\Rule;
use Livewire\Form;

class WbpForm extends Form
{
    public string $registration_number = '';

    public string $name = '';

    public string $gender = '';

    public ?string $crime_type = null;

    public ?string $admission_date = null;

    public ?string $placement_date = null;

    public ?string $expiration_date = null;

    public string $status = 'active';

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
            'crime_type' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date',
            'placement_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'status' => ['required', Rule::enum(InmateStatus::class)],
            'current_room_id' => 'nullable|exists:rooms,id',
        ];
    }

    public function setInmate(Inmate $inmate): void
    {
        $this->registration_number = $inmate->registration_number;
        $this->name = $inmate->name;
        $this->gender = $inmate->gender->value;
        $this->crime_type = $inmate->crime_type;
        $this->admission_date = $inmate->admission_date?->format('Y-m-d');
        $this->placement_date = $inmate->placement_date?->format('Y-m-d');
        $this->expiration_date = $inmate->expiration_date?->format('Y-m-d');
        $this->status = $inmate->status->value;
        $this->current_room_id = $inmate->current_room_id;
    }

    public function store(): Inmate
    {
        $this->validate();

        if ($this->current_room_id) {
            $room = Room::findOrFail($this->current_room_id);
            abort_if($room->current_occupancy >= $room->capacity, 422, 'Kamar sudah penuh.');
        }

        $inmate = Inmate::create($this->payload());

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

        $inmate->update($this->payload());

        if ($oldRoomId !== $newRoomId) {
            if ($oldRoomId) {
                Room::where('id', $oldRoomId)->decrement('current_occupancy');
            }
            if ($newRoomId) {
                Room::where('id', $newRoomId)->increment('current_occupancy');
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'registration_number' => $this->registration_number,
            'name' => $this->name,
            'gender' => $this->gender,
            'crime_type' => $this->crime_type ?: null,
            'admission_date' => $this->admission_date ?: null,
            'placement_date' => $this->placement_date ?: null,
            'expiration_date' => $this->expiration_date ?: null,
            'status' => $this->status,
            'current_room_id' => $this->current_room_id,
        ];
    }
}
