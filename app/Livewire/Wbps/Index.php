<?php

namespace App\Livewire\Wbps;

use App\Enums\UserRole;
use App\Models\Inmate;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::dashboard')]
#[Title('Daftar WBP - SIMKAR')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $gender = '';

    #[Url(as: 'room')]
    public string $roomId = '';

    public ?int $deleteId = null;

    public string $deleteName = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGender(): void
    {
        $this->resetPage();
    }

    public function updatedRoomId(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id, string $name): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);

        $this->deleteId = $id;
        $this->deleteName = $name;

        $this->dispatch('open-delete-modal', id: 'delete-wbp');
    }

    public function delete(): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);

        $inmate = Inmate::findOrFail($this->deleteId);

        if ($inmate->current_room_id) {
            Room::where('id', $inmate->current_room_id)->decrement('current_occupancy');
        }

        $inmate->delete();

        $this->deleteId = null;
        $this->deleteName = '';

        $this->dispatch('close-delete-modal');
    }

    public function render(): View
    {
        $wbps = Inmate::with('currentRoom')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('registration_number', 'like', "%{$this->search}%");
            }))
            ->when($this->gender, fn ($q) => $q->where('gender', $this->gender))
            ->when($this->roomId, fn ($q) => $q->where('current_room_id', $this->roomId))
            ->orderBy('name')
            ->paginate(10);

        $rooms = Room::orderBy('name')->get(['id', 'name']);

        return view('livewire.wbps.index', compact('wbps', 'rooms'));
    }
}
