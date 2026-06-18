<div>
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h1>
      <p class="mt-1 text-sm text-gray-500">Kelola akun dan hak akses pengguna SIMKAR.</p>
    </div>
    <a
      href="{{ route('users.create') }}"
      wire:navigate
      class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
    >
      <x-icons.plus class="h-4 w-4" />
      Tambah Pengguna
    </a>
  </div>

  @if ($successMessage || session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" role="alert">
      {{ $successMessage ?? session('success') }}
    </div>
  @endif

  @if ($errorMessage || session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
      {{ $errorMessage ?? session('error') }}
    </div>
  @endif

  <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row">
      <div class="flex-1">
        <label for="user-search" class="sr-only">Cari pengguna</label>
        <input
          id="user-search"
          wire:model.live.debounce.300ms="search"
          type="search"
          placeholder="Cari nama atau email..."
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
      </div>
      <div class="sm:w-52">
        <label for="role-filter" class="sr-only">Filter peran</label>
        <select
          id="role-filter"
          wire:model.live="role"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Semua Peran</option>
          <option value="ADMIN">ADMIN</option>
          <option value="OFFICER">OFFICER</option>
        </select>
      </div>
      @if ($search !== '' || $role !== '')
        <button
          type="button"
          wire:click="resetFilters"
          class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          Reset
        </button>
      @endif
    </div>
  </div>

  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    @if ($users->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.users-group class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">No users found.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase">
            <tr>
              <th class="px-6 py-3 font-medium">Nama</th>
              <th class="px-6 py-3 font-medium">Email</th>
              <th class="px-6 py-3 font-medium">Peran</th>
              <th class="px-6 py-3 font-medium">Dibuat</th>
              <th class="px-6 py-3 text-right font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($users as $user)
              <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="{{ $user->role === \App\Enums\UserRole::Admin ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }} inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium">
                    {{ strtoupper($user->role->value) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->created_at->format('d M Y, H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center justify-end gap-3">
                    <a
                      href="{{ route('users.edit', $user) }}"
                      wire:navigate
                      class="text-amber-600 hover:text-amber-800"
                      title="Edit {{ $user->name }}"
                    >
                      <x-icons.pencil class="h-4 w-4" />
                    </a>
                    <button
                      type="button"
                      wire:click="confirmDelete({{ $user->id }})"
                      class="text-red-600 hover:text-red-800 disabled:cursor-not-allowed disabled:opacity-30"
                      title="{{ auth()->id() === $user->id ? 'Akun sendiri tidak dapat dihapus' : 'Hapus '.$user->name }}"
                      @disabled(auth()->id() === $user->id)
                    >
                      <x-icons.trash class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($users->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">{{ $users->links() }}</div>
      @endif
    @endif
  </div>

  <x-delete-modal
    id="delete-user"
    title="Hapus Pengguna"
    :message="'Apakah Anda yakin ingin menghapus pengguna ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
  />
</div>
