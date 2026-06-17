<?php

namespace App\Livewire\Rooms;

use App\Enums\UserRole;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Tambah Kamar - SIMKAR')]
class Create extends Component
{
    public RoomForm $form;

    public function mount(): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);
    }

    public function save(): void
    {
        $this->form->store();

        session()->flash('success', 'Kamar berhasil ditambahkan.');

        $this->redirect(route('rooms.index'), navigate: true);
    }
}
