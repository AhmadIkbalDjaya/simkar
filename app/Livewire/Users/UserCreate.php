<?php

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Tambah Pengguna - SIMKAR')]
class UserCreate extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = '';

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['ADMIN', 'OFFICER'])],
        ];
    }

    public function save(): void
    {
        $this->authorizeAdmin();
        $validated = $this->validate();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::from(strtolower($validated['role'])),
        ]);

        session()->flash('success', 'Pengguna berhasil ditambahkan.');
        $this->redirect(route('users.index'), navigate: true);
    }

    public function render(): View
    {
        $this->authorizeAdmin();

        return view('livewire.users.user-create');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Admin, 403);
    }
}
