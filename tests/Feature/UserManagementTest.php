<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Users\UserCreate;
use App\Livewire\Users\UserEdit;
use App\Livewire\Users\UserIndex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('users.index'))->assertRedirect(route('login'));
    }

    public function test_officers_cannot_access_user_management_routes(): void
    {
        $officer = User::factory()->officer()->create();
        $user = User::factory()->create();

        $this->actingAs($officer);

        $this->get(route('users.index'))->assertForbidden();
        $this->get(route('users.create'))->assertForbidden();
        $this->get(route('users.edit', $user))->assertForbidden();
    }

    public function test_admin_can_access_all_user_management_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin);

        $this->get(route('users.index'))->assertOk();
        $this->get(route('users.create'))->assertOk();
        $this->get(route('users.edit', $user))->assertOk();
    }

    public function test_index_searches_name_and_email_and_filters_role(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Main Administrator']);
        User::factory()->officer()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.test']);
        User::factory()->officer()->create(['name' => 'Siti Aminah', 'email' => 'special@example.test']);

        $this->actingAs($admin);

        Livewire::test(UserIndex::class)
            ->set('search', 'Budi')
            ->assertSee('budi@example.test')
            ->assertDontSee('special@example.test')
            ->set('search', 'special@example')
            ->assertSee('Siti Aminah')
            ->set('search', '')
            ->set('role', 'ADMIN')
            ->assertSee('Main Administrator')
            ->assertDontSee('Budi Santoso');
    }

    public function test_admin_can_create_a_user_with_a_hashed_password(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(UserCreate::class)
            ->set('name', 'New Officer')
            ->set('email', 'officer@example.test')
            ->set('password', 'secret123')
            ->set('role', 'OFFICER')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('users.index'));

        $user = User::where('email', 'officer@example.test')->firstOrFail();
        $this->assertSame(UserRole::Officer, $user->role);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_create_validates_required_unique_and_role_fields(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'taken@example.test']);
        $this->actingAs($admin);

        Livewire::test(UserCreate::class)
            ->set('email', 'taken@example.test')
            ->set('password', 'short')
            ->set('role', 'INVALID')
            ->call('save')
            ->assertHasErrors(['name' => 'required', 'email' => 'unique', 'password' => 'min', 'role' => 'in']);
    }

    public function test_admin_can_edit_a_user_without_changing_the_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->officer()->create(['password' => Hash::make('original-password')]);
        $originalPassword = $user->password;
        $this->actingAs($admin);

        Livewire::test(UserEdit::class, ['user' => $user])
            ->set('name', 'Updated User')
            ->set('email', 'updated@example.test')
            ->set('role', 'ADMIN')
            ->set('password', '')
            ->call('update')
            ->assertHasNoErrors()
            ->assertRedirect(route('users.index'));

        $user->refresh();
        $this->assertSame('Updated User', $user->name);
        $this->assertSame(UserRole::Admin, $user->role);
        $this->assertSame($originalPassword, $user->password);
    }

    public function test_edit_can_change_the_password(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->officer()->create();
        $this->actingAs($admin);

        Livewire::test(UserEdit::class, ['user' => $user])
            ->set('password', 'replacement-password')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('replacement-password', $user->fresh()->password));
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(UserIndex::class)
            ->call('deleteUser', $admin->id)
            ->assertSet('errorMessage', 'You cannot delete your own account.')
            ->assertSee('You cannot delete your own account.');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->officer()->create();
        $this->actingAs($admin);

        Livewire::test(UserIndex::class)->call('deleteUser', $user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_last_admin_cannot_be_demoted(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(UserEdit::class, ['user' => $admin])
            ->set('role', 'OFFICER')
            ->call('update')
            ->assertHasErrors(['role']);

        $this->assertSame(UserRole::Admin, $admin->fresh()->role);
    }
}
