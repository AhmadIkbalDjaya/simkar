<?php

namespace App\Livewire\Rooms;

use App\Enums\UserRole;
use App\Models\Block;
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

    #[Url(as: 'block')]
    public string $blockId = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedBlockId(): void
    {
        $this->resetPage();
    }

    public ?int $deleteId = null;

    public string $deleteName = '';

    public function confirmDelete(int $id, string $name): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);

        $this->deleteId = $id;
        $this->deleteName = $name;

        $this->dispatch('open-delete-modal', id: 'delete-room');
    }

    public function delete(): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin), 403);

        Room::findOrFail($this->deleteId)->delete();

        $this->deleteId = null;
        $this->deleteName = '';

        $this->dispatch('close-delete-modal');
        $this->dispatch('toast', type: 'success', message: 'Kamar berhasil dihapus.');
    }

    public function render(): View
    {
        $rooms = Room::query()
            ->with('block')
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('code', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%")))
            ->when($this->blockId, fn ($q) => $q->where('block_id', $this->blockId))
            ->orderBy('code')
            ->paginate(10);

        $blocks = Block::orderBy('code')->get(['id', 'code', 'name']);

        return view('livewire.rooms.index', compact('rooms', 'blocks'));
    }
}
