<?php

namespace App\Livewire\Rooms;

use App\Enums\UserRole;
use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class Edit extends Component
{
    public Room $room;
    public RoomForm $form;

    public function mount(Room $room): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $this->room = $room;
        $this->form->setRoom($room);
    }

    public function getTitle(): string
    {
        return "Edit {$this->room->name} - SIMKAR";
    }

    public function save(): void
    {
        $this->form->update($this->room);

        session()->flash('success', 'Kamar berhasil diperbarui.');

        $this->redirect(route('rooms.show', $this->room), navigate: true);
    }
}
