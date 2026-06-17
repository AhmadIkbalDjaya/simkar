<?php

namespace App\Livewire\Wbps;

use App\Enums\UserRole;
use App\Models\Inmate;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class Edit extends Component
{
    public Inmate $wbp;

    public WbpForm $form;

    public function mount(Inmate $wbp): void
    {
        abort_unless(auth()->user()->role === UserRole::Admin, 403);

        $this->wbp = $wbp;
        $this->form->setInmate($wbp);
    }

    public function getTitle(): string
    {
        return "Edit {$this->wbp->name} - SIMKAR";
    }

    public function save(): void
    {
        $this->form->update($this->wbp);

        session()->flash('success', 'WBP berhasil diperbarui.');

        $this->redirect(route('wbps.show', $this->wbp), navigate: true);
    }

    public function render(): View
    {
        $rooms = Room::orderBy('name')->get(['id', 'name', 'capacity', 'current_occupancy']);

        return view('livewire.wbps.edit', compact('rooms'));
    }
}
