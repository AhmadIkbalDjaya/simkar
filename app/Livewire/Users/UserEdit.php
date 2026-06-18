<?php

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::dashboard')]
class UserEdit extends Component
{
    public User $user;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = '';

    public function mount(User $user): void
    {
        $this->authorizeAdmin();

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = strtoupper($user->role->value);
    }

    public function getTitle(): string
    {
        return "Edit {$this->user->name} - SIMKAR";
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['ADMIN', 'OFFICER'])],
        ];
    }

    public function update(): void
    {
        $this->authorizeAdmin();
        $validated = $this->validate();
        $newRole = UserRole::from(strtolower($validated['role']));

        if ($this->user->role === UserRole::Admin
            && $newRole === UserRole::Officer
            && User::where('role', UserRole::Admin->value)->count() <= 1) {
            $this->addError('role', 'Setidaknya satu akun administrator harus tetap tersedia.');

            return;
        }

        $attributes = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $newRole,
        ];

        if ($validated['password'] !== null && $validated['password'] !== '') {
            $attributes['password'] = Hash::make($validated['password']);
        }

        $this->user->update($attributes);

        session()->flash('success', 'Pengguna berhasil diperbarui.');
        $this->redirect(route('users.index'), navigate: true);
    }

    public function render(): View
    {
        $this->authorizeAdmin();

        return view('livewire.users.user-edit');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Admin, 403);
    }
}
