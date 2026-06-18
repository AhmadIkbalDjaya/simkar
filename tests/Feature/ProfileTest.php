<?php

namespace Tests\Feature;

use App\Livewire\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->officer()->create();

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee($user->email);
    }

    public function test_user_can_update_their_profile(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('name', 'Nama Baru')
            ->set('email', 'baru@example.test')
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru',
            'email' => 'baru@example.test',
        ]);
    }

    public function test_profile_email_must_be_unique(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('email', $otherUser->email)
            ->call('updateProfile')
            ->assertHasErrors(['email' => 'unique']);
    }

    public function test_user_can_change_password_with_their_current_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('current-password')]);
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('currentPassword', 'current-password')
            ->set('password', 'new-password-123')
            ->set('passwordConfirmation', 'new-password-123')
            ->call('updatePassword')
            ->assertHasNoErrors()
            ->assertSet('currentPassword', '')
            ->assertSet('password', '')
            ->assertSet('passwordConfirmation', '');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => Hash::make('current-password')]);
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('currentPassword', 'wrong-password')
            ->set('password', 'new-password-123')
            ->set('passwordConfirmation', 'new-password-123')
            ->call('updatePassword')
            ->assertHasErrors(['currentPassword' => 'current_password']);

        $this->assertTrue(Hash::check('current-password', $user->fresh()->password));
    }

    public function test_new_password_must_be_confirmed(): void
    {
        $user = User::factory()->create(['password' => Hash::make('current-password')]);
        $this->actingAs($user);

        Livewire::test(Profile::class)
            ->set('currentPassword', 'current-password')
            ->set('password', 'new-password-123')
            ->set('passwordConfirmation', 'different-password')
            ->call('updatePassword')
            ->assertHasErrors(['password' => 'same']);
    }
}
