<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::dashboard')]
#[Title('Profil - SIMKAR')]
class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $currentPassword = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount(): void
    {
        $user = $this->user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = $this->user();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
        ]);

        $user->update($validated);

        $this->dispatch('toast', type: 'success', message: 'Profil berhasil diperbarui.');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'password' => ['required', Password::min(8), 'same:passwordConfirmation'],
            'passwordConfirmation' => ['required'],
        ], [
            'currentPassword.current_password' => 'Password saat ini tidak sesuai.',
            'password.same' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $this->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('currentPassword', 'password', 'passwordConfirmation');
        $this->dispatch('toast', type: 'success', message: 'Password berhasil diubah.');
    }

    public function render(): View
    {
        return view('livewire.profile');
    }

    private function user(): User
    {
        return auth()->user();
    }
}
