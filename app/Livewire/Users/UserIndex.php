<?php

namespace App\Livewire\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts::dashboard')]
#[Title('Kelola Pengguna - SIMKAR')]
class UserIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $role = '';

    public ?int $deleteId = null;

    public string $deleteName = '';

    public ?string $errorMessage = null;

    public ?string $successMessage = null;

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'role');
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);
        $this->deleteId = $user->id;
        $this->deleteName = $user->name;

        $this->dispatch('open-delete-modal', id: 'delete-user');
    }

    public function delete(): void
    {
        abort_if($this->deleteId === null, 404);

        $this->deleteUser($this->deleteId);
        $this->dispatch('close-delete-modal');
    }

    public function deleteUser(int $userId): void
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($userId);

        if ($user->is(auth()->user())) {
            $this->errorMessage = 'You cannot delete your own account.';

            return;
        }

        if ($user->role === UserRole::Admin && User::where('role', UserRole::Admin->value)->count() <= 1) {
            $this->errorMessage = 'At least one administrator account must remain.';

            return;
        }

        $user->delete();
        $this->reset('deleteId', 'deleteName');
        $this->errorMessage = null;
        $this->successMessage = 'Pengguna berhasil dihapus.';
        $this->resetPage();
    }

    public function render(): View
    {
        $this->authorizeAdmin();

        $users = User::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->role !== '', fn ($query) => $query->where('role', strtolower($this->role)))
            ->latest()
            ->paginate(10);

        return view('livewire.users.user-index', compact('users'));
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === UserRole::Admin, 403);
    }
}
