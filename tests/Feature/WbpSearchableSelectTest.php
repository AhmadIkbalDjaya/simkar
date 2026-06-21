<?php

namespace Tests\Feature;

use App\Livewire\Wbps\Create;
use App\Livewire\Wbps\Index;
use App\Models\Inmate;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WbpSearchableSelectTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form_uses_searchable_room_select_and_marks_full_rooms_disabled(): void
    {
        $availableRoom = Room::factory()->create([
            'name' => 'Kamar Melati',
            'capacity' => 5,
            'current_occupancy' => 2,
        ]);
        $fullRoom = Room::factory()->create([
            'name' => 'Kamar Anggrek',
            'capacity' => 3,
            'current_occupancy' => 3,
        ]);

        $this->actingAs(User::factory()->admin()->create());

        Livewire::test(Create::class)
            ->assertSee('id="form-current_room_id-search"', escape: false)
            ->assertSee('overflow-visible', escape: false)
            ->assertSee('data-model="form.current_room_id"', escape: false)
            ->assertSee('Cari kamar saat ini...')
            ->assertSee($availableRoom->name)
            ->assertSee($fullRoom->name)
            ->assertSee('Penuh')
            ->assertSee('&quot;disabled&quot;:true', escape: false);
    }

    public function test_room_filter_uses_searchable_select_and_filters_wbps(): void
    {
        $firstRoom = Room::factory()->create(['name' => 'Kamar Satu']);
        $secondRoom = Room::factory()->create(['name' => 'Kamar Dua']);
        $firstInmate = Inmate::factory()->create([
            'name' => 'Penghuni Pertama',
            'current_room_id' => $firstRoom->id,
        ]);
        $secondInmate = Inmate::factory()->create([
            'name' => 'Penghuni Kedua',
            'current_room_id' => $secondRoom->id,
        ]);

        $this->actingAs(User::factory()->officer()->create());

        Livewire::test(Index::class)
            ->assertSee('id="wbp-room-search"', escape: false)
            ->assertSee('overflow-visible', escape: false)
            ->assertSee('data-model="roomId"', escape: false)
            ->assertSee('data-empty-value="&quot;&quot;"', escape: false)
            ->assertSee('Semua Kamar')
            ->assertSee($firstRoom->name)
            ->assertSee($secondRoom->name)
            ->set('roomId', (string) $firstRoom->id)
            ->assertSee($firstInmate->name)
            ->assertDontSee($secondInmate->name);
    }
}
