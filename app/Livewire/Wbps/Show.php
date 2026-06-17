<?php

namespace App\Livewire\Wbps;

use App\Models\Inmate;
use App\Models\RoomTransfer;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class Show extends Component
{
    public Inmate $wbp;

    public Collection $transfers;

    public function mount(Inmate $wbp): void
    {
        $this->wbp = $wbp->load('currentRoom');
        $this->transfers = RoomTransfer::with(['roomFrom', 'roomTo'])
            ->where('inmate_id', $wbp->id)
            ->latest('transferred_at')
            ->limit(10)
            ->get();
    }

    public function getTitle(): string
    {
        return "{$this->wbp->name} - SIMKAR";
    }
}
