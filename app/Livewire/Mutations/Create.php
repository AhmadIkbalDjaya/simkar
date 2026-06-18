<?php

namespace App\Livewire\Mutations;

use App\Enums\UserRole;
use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Buat Mutasi - SIMKAR')]
class Create extends Component
{
    #[Validate('required|exists:inmates,id')]
    public ?int $inmate_id = null;

    public ?int $room_from_id = null;

    public ?string $room_from_name = null;

    #[Validate('required|exists:rooms,id')]
    public ?int $room_to_id = null;

    #[Validate('required|date')]
    public string $transferred_at = '';

    #[Validate('required|string|max:255')]
    public string $officer_name = '';

    #[Validate('nullable|string')]
    public ?string $notes = '';

    #[Validate('required|string')]
    public string $officer_signature = '';

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->transferred_at = now()->format('Y-m-d\TH:i');
        $this->officer_name = auth()->user()->name;
    }

    public function updatedInmateId(): void
    {
        $this->room_from_id = null;
        $this->room_from_name = null;
        $this->room_to_id = null;

        if ($this->inmate_id) {
            $inmate = Inmate::with('currentRoom')->find($this->inmate_id);

            if ($inmate?->currentRoom) {
                $this->room_from_id = $inmate->current_room_id;
                $this->room_from_name = $inmate->currentRoom->name;
            }
        }
    }

    public function rules(): array
    {
        return [
            'inmate_id' => 'required|exists:inmates,id',
            'room_from_id' => 'required|exists:rooms,id',
            'room_to_id' => ['required', 'exists:rooms,id', 'different:room_from_id'],
            'transferred_at' => 'required|date',
            'officer_name' => 'required|string|max:255',
            'officer_signature' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'inmate_id' => 'WBP',
            'room_from_id' => 'kamar asal',
            'room_to_id' => 'kamar tujuan',
            'transferred_at' => 'waktu mutasi',
            'officer_name' => 'nama petugas',
            'officer_signature' => 'tanda tangan',
        ];
    }

    public function save(): void
    {
        $this->authorizeAccess();
        $this->validate();

        DB::transaction(function () {
            $inmate = Inmate::lockForUpdate()->findOrFail($this->inmate_id);

            abort_if($inmate->current_room_id !== $this->room_from_id, 422, 'Kamar asal WBP telah berubah. Silakan muat ulang halaman.');

            $roomTo = Room::lockForUpdate()->findOrFail($this->room_to_id);

            abort_if($roomTo->current_occupancy >= $roomTo->capacity, 422, 'Kamar tujuan sudah penuh.');

            $transfer = RoomTransfer::create([
                'inmate_id' => $this->inmate_id,
                'room_from_id' => $this->room_from_id,
                'room_to_id' => $this->room_to_id,
                'transferred_at' => $this->transferred_at,
                'officer_name' => $this->officer_name,
                'officer_signature' => $this->officer_signature,
                'notes' => $this->notes ?: null,
                'created_by' => auth()->id(),
            ]);

            $inmate->update(['current_room_id' => $this->room_to_id]);

            Room::where('id', $this->room_from_id)
                ->where('current_occupancy', '>', 0)
                ->decrement('current_occupancy');

            $roomTo->increment('current_occupancy');

            session()->flash('success', 'Mutasi berhasil disimpan.');

            $this->redirect(route('mutations.show', $transfer), navigate: true);
        });
    }

    public function render(): View
    {
        $this->authorizeAccess();

        $inmates = Inmate::whereNotNull('current_room_id')
            ->orderBy('name')
            ->get(['id', 'name', 'registration_number']);

        $availableRooms = Room::whereRaw('current_occupancy < capacity')
            ->when($this->room_from_id, fn ($q) => $q->where('id', '!=', $this->room_from_id))
            ->orderBy('name')
            ->get(['id', 'name', 'capacity', 'current_occupancy']);

        return view('livewire.mutations.create', compact('inmates', 'availableRooms'));
    }

    private function authorizeAccess(): void
    {
        abort_unless(auth()->user()?->hasRole(UserRole::Admin, UserRole::Officer), 403);
    }
}
