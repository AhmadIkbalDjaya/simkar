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
