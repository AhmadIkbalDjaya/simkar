<?php

namespace Tests\Feature;

use App\Models\RoomTransfer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_an_empty_state_when_there_are_no_transfers(): void
    {
        $user = User::factory()->officer()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Belum ada mutasi kamar')
            ->assertSee('Catat mutasi');
    }

    public function test_dashboard_shows_recent_transfer_details(): void
    {
        $user = User::factory()->officer()->create();
        $transfer = RoomTransfer::factory()->create([
            'officer_name' => 'Petugas Uji',
            'created_by' => $user,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($transfer->inmate->name)
            ->assertSee($transfer->roomFrom->name)
            ->assertSee($transfer->roomTo->name)
            ->assertSee('Petugas Uji');
    }
}
