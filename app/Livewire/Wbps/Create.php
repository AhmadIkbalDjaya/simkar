<?php

namespace App\Livewire\Wbps;

use App\Enums\UserRole;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Tambah WBP - SIMKAR')]
class Create extends Component
{
    public WbpForm $form;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);
    }

    public function save(): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);

        $this->form->store();

        session()->flash('success', 'WBP berhasil ditambahkan.');

        $this->redirect(route('wbps.index'), navigate: true);
    }

    public function render(): View
    {
        $rooms = Room::orderBy('name')->get(['id', 'name', 'capacity', 'current_occupancy']);

        return view('livewire.wbps.create', compact('rooms'));
    }
}
