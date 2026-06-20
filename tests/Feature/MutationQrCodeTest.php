<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Livewire\Mutations\Create;
use App\Models\Inmate;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MutationQrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_and_room_qr_codes_are_generated_as_distinct_png_images(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();

        $this->actingAs($officer);

        $generalQr = $this->get(route('mutations.qr.image'))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
        $roomQr = $this->get(route('mutations.qr.image', ['room' => $room->id]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $generalQr->getContent());
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $roomQr->getContent());
        $this->assertNotSame(hash('sha256', $generalQr->getContent()), hash('sha256', $roomQr->getContent()));

        $this->get(route('mutations.qr.image', ['room' => $room->id, 'download' => 1]))
            ->assertOk()
            ->assertHeader('Content-Disposition', "attachment; filename=\"qr-mutasi-kamar-{$room->id}.png\"");
    }

    public function test_qr_management_is_available_to_admins_and_officers(): void
    {
        $room = Room::factory()->create();

        foreach ([User::factory()->admin()->create(), User::factory()->officer()->create()] as $user) {
            $this->actingAs($user)
                ->get(route('mutations.index'))
                ->assertOk()
                ->assertSee('QR Input Mutasi')
                ->assertSee('Download')
                ->assertSee('Print')
                ->assertSee('Copy Link')
                ->assertSee('Bagikan');

            $this->actingAs($user)
                ->get(route('rooms.show', $room))
                ->assertOk()
                ->assertSee('QR Kamar')
                ->assertSee(route('mutations.create', ['room' => $room->id]), escape: false);

            $this->actingAs($user)
                ->get(route('mutations.qr.print', ['room' => $room->id]))
                ->assertOk()
                ->assertSee("QR Mutasi {$room->name}")
                ->assertSee(route('mutations.create', ['room' => $room->id]), escape: false);
        }
    }

    public function test_guest_returns_to_scanned_room_mutation_form_after_login(): void
    {
        $officer = User::factory()->officer()->create();
        $room = Room::factory()->create();
        $targetUrl = route('mutations.create', ['room' => $room->id]);

        $this->get($targetUrl)
            ->assertRedirect(route('login'));

        $this->assertSame($targetUrl, session('url.intended'));

        Livewire::test(Login::class)
            ->set('email', $officer->email)
            ->set('password', 'password')
            ->call('login')
            ->assertRedirect($targetUrl);
    }

    public function test_general_form_has_no_destination_room_selected(): void
    {
        $this->actingAs(User::factory()->officer()->create());

        Livewire::test(Create::class)
            ->assertSet('room_to_id', null)
            ->assertSet('roomQueryError', null);
    }

    public function test_scanned_room_is_prefilled_and_preserved_after_selecting_an_inmate(): void
    {
        $officer = User::factory()->officer()->create();
        $sourceRoom = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 1,
        ]);
        $destinationRoom = Room::factory()->create([
            'capacity' => 5,
            'current_occupancy' => 0,
        ]);
        $inmate = Inmate::factory()->create(['current_room_id' => $sourceRoom->id]);

        $this->actingAs($officer);

        Livewire::withQueryParams(['room' => $destinationRoom->id])
            ->test(Create::class)
            ->assertSet('room_to_id', $destinationRoom->id)
            ->set('inmate_id', $inmate->id)
            ->assertSet('room_from_id', $sourceRoom->id)
            ->assertSet('room_to_id', $destinationRoom->id)
            ->assertHasNoErrors('room_to_id');
    }

    public function test_invalid_room_query_leaves_destination_empty_with_an_error_message(): void
    {
        $this->actingAs(User::factory()->officer()->create());

        Livewire::withQueryParams(['room' => 'not-a-room'])
            ->test(Create::class)
            ->assertSet('room_to_id', null)
            ->assertSet('roomQueryError', 'QR kamar tidak valid atau kamar sudah tidak tersedia.')
            ->assertSee('Silakan pilih kamar tujuan secara manual.');
    }

    public function test_full_scanned_room_is_prefilled_but_rejected(): void
    {
        $room = Room::factory()->create([
            'capacity' => 2,
            'current_occupancy' => 2,
        ]);

        $this->actingAs(User::factory()->officer()->create());

        Livewire::withQueryParams(['room' => $room->id])
            ->test(Create::class)
            ->assertSet('room_to_id', $room->id)
            ->assertHasErrors('room_to_id')
            ->assertSee('Penuh');
    }

    public function test_scanned_room_matching_the_inmates_source_room_is_rejected(): void
    {
        $room = Room::factory()->create([
            'capacity' => 2,
            'current_occupancy' => 1,
        ]);
        $inmate = Inmate::factory()->create(['current_room_id' => $room->id]);

        $this->actingAs(User::factory()->officer()->create());

        Livewire::withQueryParams(['room' => $room->id])
            ->test(Create::class)
            ->set('inmate_id', $inmate->id)
            ->assertSet('room_to_id', $room->id)
            ->assertHasErrors('room_to_id')
            ->assertSee('WBP sudah berada di kamar tujuan ini.');
    }

    public function test_invalid_room_qr_image_returns_not_found(): void
    {
        $this->actingAs(User::factory()->officer()->create())
            ->get(route('mutations.qr.image', ['room' => 999999]))
            ->assertNotFound();
    }
}
