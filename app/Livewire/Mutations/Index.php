<?php

namespace App\Livewire\Mutations;

use App\Models\Room;
use App\Models\RoomTransfer;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::dashboard')]
#[Title('Riwayat Mutasi - SIMKAR')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'from')]
    public string $dateFrom = '';

    #[Url(as: 'to')]
    public string $dateTo = '';

    #[Url]
    public string $officer = '';

    #[Url(as: 'room_from')]
    public string $roomFromId = '';

    #[Url(as: 'room_to')]
    public string $roomToId = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedOfficer(): void
    {
        $this->resetPage();
    }

    public function updatedRoomFromId(): void
    {
        $this->resetPage();
    }

    public function updatedRoomToId(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'dateFrom', 'dateTo', 'officer', 'roomFromId', 'roomToId']);
        $this->resetPage();
    }

    public function render(): View
    {
        $mutations = RoomTransfer::with(['inmate', 'roomFrom', 'roomTo'])
            ->when($this->search, fn ($q) => $q->whereHas('inmate', fn ($q) => $q->where('name', 'like', "%{$this->search}%")))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('transferred_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('transferred_at', '<=', $this->dateTo))
            ->when($this->officer, fn ($q) => $q->where('officer_name', 'like', "%{$this->officer}%"))
            ->when($this->roomFromId, fn ($q) => $q->where('room_from_id', $this->roomFromId))
            ->when($this->roomToId, fn ($q) => $q->where('room_to_id', $this->roomToId))
            ->latest('transferred_at')
            ->paginate(10);

        $rooms = Room::orderBy('name')->get(['id', 'name']);

        return view('livewire.mutations.index', compact('mutations', 'rooms'));
    }
}
