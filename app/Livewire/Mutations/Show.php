<?php

namespace App\Livewire\Mutations;

use App\Models\RoomTransfer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class Show extends Component
{
    public RoomTransfer $mutation;

    public function mount(RoomTransfer $mutation): void
    {
        $this->mutation = $mutation->load(['inmate', 'roomFrom', 'roomTo', 'creator']);
    }

    public function getTitle(): string
    {
        return "Detail Mutasi - SIMKAR";
    }
}
