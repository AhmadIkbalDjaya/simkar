<?php

namespace Tests\Feature;

use App\Livewire\Mutations\Create;
use App\Models\Inmate;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateMutationTest extends TestCase
{
    use RefreshDatabase;

    public function test_wbp_dropdown_only_contains_inmates_assigned_to_a_room(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $assigned = Inmate::factory()->create([
            'name' => 'Andi Saputra',
            'registration_number' => 'WBP-SEARCH-001',
            'current_room_id' => $room->id,
        ]);
        $unassigned = Inmate::factory()->create([
            'name' => 'Belum Ditempatkan',
            'registration_number' => 'WBP-SEARCH-002',
            'current_room_id' => null,
        ]);

        $this->actingAs($officer);

        $component = Livewire::test(Create::class)
            ->assertSee('id="inmate_id-search"', escape: false)
            ->assertSee('id="room_to_id-search"', escape: false)
            ->assertSee('data-model="inmate_id"', escape: false)
            ->assertSee('data-model="room_to_id"', escape: false)
            ->assertSee('Cari nama atau nomor registrasi WBP...')
            ->assertSee('Cari kamar tujuan...')
            ->assertSee('WBP tidak ditemukan.')
            ->assertSee('Kamar tujuan tidak ditemukan.')
            ->assertSee($assigned->name)
            ->assertSee($assigned->registration_number)
            ->assertDontSee($unassigned->name)
            ->assertDontSee($unassigned->registration_number);

        $this->assertSame(2, substr_count($component->html(), 'role="combobox"'));
    }

    public function test_destination_room_dropdown_only_contains_available_rooms_with_occupancy(): void
    {
        $officer = User::factory()->officer()->create();
        $availableRoom = Room::factory()->create([
            'name' => 'Kamar Tersedia',
            'capacity' => 5,
            'current_occupancy' => 2,
        ]);
        $fullRoom = Room::factory()->create([
            'name' => 'Kamar Penuh',
            'capacity' => 5,
            'current_occupancy' => 5,
        ]);

        $this->actingAs($officer);

        Livewire::test(Create::class)
            ->assertSee($availableRoom->name)
            ->assertSee('2\/5', escape: false)
            ->assertDontSee($fullRoom->name);
    }

    public function test_selecting_a_wbp_updates_the_source_room(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $inmate = Inmate::factory()->create(['current_room_id' => $room->id]);

        $this->actingAs($officer);

        Livewire::test(Create::class)
            ->set('inmate_id', $inmate->id)
            ->assertSet('room_from_id', $room->id)
            ->assertSet('room_from_name', $room->name);
    }

    public function test_searchable_selects_display_validation_errors(): void
    {
        $officer = User::factory()->officer()->create();

        $this->actingAs($officer);

        Livewire::test(Create::class)
            ->call('save')
            ->assertHasErrors([
                'inmate_id' => 'required',
                'room_to_id' => 'required',
            ])
            ->assertSee('inmate_id-error', escape: false)
            ->assertSee('room_to_id-error', escape: false);
    }

    public function test_officer_can_create_a_mutation(): void
    {
        $officer = User::factory()->officer()->create();
        $roomFrom = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 1,
        ]);
        $roomTo = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 0,
        ]);
        $inmate = Inmate::factory()->create(['current_room_id' => $roomFrom->id]);

        $this->actingAs($officer);

        Livewire::test(Create::class)
            ->set('inmate_id', $inmate->id)
            ->set('room_to_id', $roomTo->id)
            ->set('transferred_at', '2026-06-18T10:30')
            ->set('officer_name', $officer->name)
            ->set('officer_signature', 'data:image/png;base64,test-signature')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('room_transfers', [
            'inmate_id' => $inmate->id,
            'room_from_id' => $roomFrom->id,
            'room_to_id' => $roomTo->id,
            'created_by' => $officer->id,
        ]);
        $this->assertDatabaseHas('inmates', [
            'id' => $inmate->id,
            'current_room_id' => $roomTo->id,
        ]);
        $this->assertSame(0, $roomFrom->fresh()->current_occupancy);
        $this->assertSame(1, $roomTo->fresh()->current_occupancy);
    }

    public function test_mutation_is_rejected_when_the_source_room_has_changed(): void
    {
        $officer = User::factory()->officer()->create();
        $originalRoom = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 1,
        ]);
        $changedRoom = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 1,
        ]);
        $roomTo = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 0,
        ]);
        $inmate = Inmate::factory()->create(['current_room_id' => $originalRoom->id]);

        $this->actingAs($officer);

        $component = Livewire::test(Create::class)
            ->set('inmate_id', $inmate->id)
            ->set('room_to_id', $roomTo->id)
            ->set('transferred_at', '2026-06-18T10:30')
            ->set('officer_name', $officer->name)
            ->set('officer_signature', 'data:image/png;base64,test-signature');

        $inmate->update(['current_room_id' => $changedRoom->id]);

        $component->call('save')->assertStatus(422);

        $this->assertDatabaseCount('room_transfers', 0);
        $this->assertDatabaseHas('inmates', [
            'id' => $inmate->id,
            'current_room_id' => $changedRoom->id,
        ]);
        $this->assertSame(1, $originalRoom->fresh()->current_occupancy);
        $this->assertSame(1, $changedRoom->fresh()->current_occupancy);
        $this->assertSame(0, $roomTo->fresh()->current_occupancy);
    }
}
