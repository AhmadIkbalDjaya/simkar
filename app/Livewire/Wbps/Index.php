<?php

namespace App\Livewire\Wbps;

use App\Enums\GenderType;
use App\Enums\InmateStatus;
use App\Enums\UserRole;
use App\Exports\WbpExport;
use App\Models\Inmate;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts::dashboard')]
#[Title('Daftar WBP - SIMKAR')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $gender = '';

    #[Url]
    public string $status = '';

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

    public function updatedStatus(): void
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
        $this->dispatch('toast', type: 'success', message: 'WBP berhasil dihapus.');
    }

    private function getFilteredQuery(): Builder
    {
        return Inmate::with('currentRoom.block')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('registration_number', 'like', "%{$this->search}%");
            }))
            ->when($this->gender, fn ($q) => $q->where('gender', $this->gender))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->when($this->roomId, fn ($q) => $q->where('current_room_id', $this->roomId))
            ->orderBy('name');
    }

    private function getFilterSummary(): string
    {
        $parts = [];

        if ($this->search) {
            $parts[] = 'Pencarian: '.$this->search;
        }
        if ($this->gender) {
            $parts[] = 'Gender: '.($this->gender === GenderType::Male->value ? 'Laki-laki' : 'Perempuan');
        }
        if ($this->status) {
            $parts[] = 'Status: '.InmateStatus::from($this->status)->label();
        }
        if ($this->roomId) {
            $room = Room::find($this->roomId);
            if ($room) {
                $parts[] = 'Kamar: '.$room->name;
            }
        }

        return $parts ? implode(' | ', $parts) : 'Semua data';
    }

    public function exportPdf()
    {
        $data = $this->getFilteredQuery()->get();

        if ($data->isEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Tidak ada data untuk diekspor.');

            return;
        }

        $pdf = Pdf::loadView('wbps.pdf', [
            'data' => $data,
            'filterSummary' => $this->getFilterSummary(),
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'daftar-wbp-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel()
    {
        /** @var Collection $data */
        $data = $this->getFilteredQuery()->get();

        if ($data->isEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Tidak ada data untuk diekspor.');

            return;
        }

        return Excel::download(
            new WbpExport($data),
            'daftar-wbp-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function render(): View
    {
        $wbps = $this->getFilteredQuery()->paginate(10);

        $rooms = Room::orderBy('name')->get(['id', 'name']);

        return view('livewire.wbps.index', compact('wbps', 'rooms'));
    }
}
