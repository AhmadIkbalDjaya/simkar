<?php

namespace App\Livewire\Reports;

use App\Exports\MutationReportExport;
use App\Models\Room;
use App\Models\RoomTransfer;
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
#[Title('Laporan Mutasi - SIMKAR')]
class MutationReport extends Component
{
    use WithPagination;

    #[Url(as: 'from')]
    public string $startDate = '';

    #[Url(as: 'to')]
    public string $endDate = '';

    #[Url]
    public string $officer = '';

    #[Url(as: 'room')]
    public string $roomId = '';

    public function updatedStartDate(): void
    {
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->resetPage();
    }

    public function updatedOfficer(): void
    {
        $this->resetPage();
    }

    public function updatedRoomId(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['startDate', 'endDate', 'officer', 'roomId']);
        $this->resetPage();
    }

    private function getFilteredQuery(): Builder
    {
        return RoomTransfer::with(['inmate', 'roomFrom', 'roomTo'])
            ->when($this->startDate, fn ($q) => $q->whereDate('transferred_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('transferred_at', '<=', $this->endDate))
            ->when($this->officer, fn ($q) => $q->where('officer_name', $this->officer))
            ->when($this->roomId, fn ($q) => $q->where(function ($q) {
                $q->where('room_from_id', $this->roomId)
                    ->orWhere('room_to_id', $this->roomId);
            }))
            ->latest('transferred_at');
    }

    private function getReportData(): Collection
    {
        return $this->getFilteredQuery()->get();
    }

    private function getFilterSummary(): string
    {
        $parts = [];

        if ($this->startDate) {
            $parts[] = 'Dari: '.date('d M Y', strtotime($this->startDate));
        }
        if ($this->endDate) {
            $parts[] = 'Sampai: '.date('d M Y', strtotime($this->endDate));
        }
        if ($this->officer) {
            $parts[] = 'Petugas: '.$this->officer;
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
        $data = $this->getReportData();

        if ($data->isEmpty()) {
            session()->flash('error', 'Tidak ada data untuk diekspor.');

            return;
        }

        $pdf = Pdf::loadView('reports.mutations.pdf', [
            'data' => $data,
            'filterSummary' => $this->getFilterSummary(),
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-mutasi-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportExcel()
    {
        $data = $this->getReportData();

        if ($data->isEmpty()) {
            session()->flash('error', 'Tidak ada data untuk diekspor.');

            return;
        }

        return Excel::download(
            new MutationReportExport($data),
            'laporan-mutasi-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function render(): View
    {
        $mutations = $this->getFilteredQuery()->paginate(10);
        $rooms = Room::orderBy('name')->get(['id', 'name']);
        $officers = RoomTransfer::distinct()->pluck('officer_name')->sort()->values();

        return view('livewire.reports.mutation-report', compact('mutations', 'rooms', 'officers'));
    }
}
