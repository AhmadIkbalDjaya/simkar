<?php

namespace App\Livewire;

use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Dashboard - SIMKAR')]
class Dashboard extends Component
{
    public int $totalInmates;

    public int $totalRooms;

    public int $totalCapacity;

    public int $currentOccupants;

    public int $transfersToday;

    public int $transfersThisMonth;

    public Collection $recentTransfers;

    public function mount(): void
    {
        $this->loadStatistics();
        $this->loadRecentTransfers();
    }

    private function loadStatistics(): void
    {
        $this->totalInmates = Inmate::count();
        $this->totalRooms = Room::count();

        $roomStats = Room::selectRaw('COALESCE(SUM(capacity), 0) as total_capacity, COALESCE(SUM(current_occupancy), 0) as total_occupancy')->first();
        $this->totalCapacity = (int) $roomStats->total_capacity;
        $this->currentOccupants = (int) $roomStats->total_occupancy;

        $today = Carbon::today();
        $this->transfersToday = RoomTransfer::whereDate('transferred_at', $today)->count();
        $this->transfersThisMonth = RoomTransfer::whereMonth('transferred_at', $today->month)
            ->whereYear('transferred_at', $today->year)
            ->count();
    }

    private function loadRecentTransfers(): void
    {
        $this->recentTransfers = RoomTransfer::with(['inmate', 'roomFrom', 'roomTo'])
            ->latest('transferred_at')
            ->limit(10)
            ->get();
    }
}
