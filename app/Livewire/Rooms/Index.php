<?php

namespace App\Livewire\Rooms;

use App\Enums\UserRole;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::dashboard')]
#[Title('Daftar Kamar - SIMKAR')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $block = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedBlock(): void
    {
        $this->resetPage();
    }

    public ?int $deleteId = null;
    public string $deleteName = '';

    public function confirmDelete(int $id, string $name): void
    {
        $this->deleteId = $id;
        $this->deleteName = $name;

        $this->dispatch('open-delete-modal', id: 'delete-room');
    }

    public function delete(): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        Room::findOrFail($this->deleteId)->delete();

        $this->deleteId = null;
        $this->deleteName = '';

        $this->dispatch('close-delete-modal');
    }

    public function render(): View
    {
        $rooms = Room::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->block, fn ($q) => $q->where('block', $this->block))
            ->orderBy('name')
            ->paginate(10);

        $blocks = Room::whereNotNull('block')->distinct()->pluck('block')->sort()->values();

        return view('livewire.rooms.index', compact('rooms', 'blocks'));
    }
}
