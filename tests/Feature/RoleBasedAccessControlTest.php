<?php

namespace Tests\Feature;

use App\Livewire\Rooms\Index as RoomIndex;
use App\Livewire\Wbps\Index as WbpIndex;
use App\Models\Inmate;
use App\Models\Room;
use App\Models\RoomTransfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_officer_can_access_operational_pages(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $inmate = Inmate::factory()->create();
        $mutation = RoomTransfer::factory()->create(['created_by' => $officer]);

        $this->actingAs($officer);

        $this->get(route('dashboard'))->assertOk();
        $this->get(route('rooms.index'))->assertOk();
        $this->get(route('rooms.show', $room))->assertOk();
        $this->get(route('wbps.index'))->assertOk();
        $this->get(route('wbps.show', $inmate))->assertOk();
        $this->get(route('mutations.index'))->assertOk();
        $this->get(route('mutations.create'))->assertOk();
        $this->get(route('mutations.show', $mutation))->assertOk();
        $this->get(route('reports.mutations'))->assertOk();
    }

    public function test_officer_cannot_access_admin_management_pages(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $inmate = Inmate::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($officer);

        $this->get(route('rooms.create'))->assertForbidden();
        $this->get(route('rooms.edit', $room))->assertForbidden();
        $this->get(route('wbps.create'))->assertForbidden();
        $this->get(route('wbps.edit', $inmate))->assertForbidden();
        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('users.create'))->assertForbidden();
        $this->get(route('users.edit', $user))->assertForbidden();
    }

    public function test_admin_can_access_every_protected_page(): void
    {
        $admin = User::factory()->admin()->create();
        $room = Room::factory()->create();
        $inmate = Inmate::factory()->create();
        $mutation = RoomTransfer::factory()->create(['created_by' => $admin]);
        $user = User::factory()->create();

        $this->actingAs($admin);

        $routes = [
            route('dashboard'),
            route('rooms.index'),
            route('rooms.create'),
            route('rooms.show', $room),
            route('rooms.edit', $room),
            route('wbps.index'),
            route('wbps.create'),
            route('wbps.show', $inmate),
            route('wbps.edit', $inmate),
            route('mutations.index'),
            route('mutations.create'),
            route('mutations.show', $mutation),
            route('reports.mutations'),
            route('users.index'),
            route('users.create'),
            route('users.edit', $user),
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertOk();
        }
    }

    public function test_officer_cannot_invoke_admin_only_livewire_actions(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $inmate = Inmate::factory()->create();

        $this->actingAs($officer);

        Livewire::test(RoomIndex::class)
            ->call('confirmDelete', $room->id, $room->name)
            ->assertForbidden();

        Livewire::test(WbpIndex::class)
            ->call('confirmDelete', $inmate->id, $inmate->name)
            ->assertForbidden();

        $this->assertDatabaseHas('rooms', ['id' => $room->id]);
        $this->assertDatabaseHas('inmates', ['id' => $inmate->id]);
    }

    public function test_officer_sidebar_hides_user_management(): void
    {
        $officer = User::factory()->officer()->create();

        $this->actingAs($officer)
            ->get(route('dashboard'))
            ->assertDontSee('Kelola Pengguna')
            ->assertSee('Narapidana')
            ->assertSee('Kamar')
            ->assertSee('Buat Mutasi')
            ->assertSee('Laporan Mutasi');
    }
}
