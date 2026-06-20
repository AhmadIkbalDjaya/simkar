<div>
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h1>
      <p class="mt-1 text-sm text-gray-500">
        Kelola akun dan hak akses pengguna SIMKAR.
      </p>
    </div>
    <x-ui.button :href="route('users.create')" wire:navigate>
      <x-icons.plus class="h-4 w-4" />
      Tambah Pengguna
    </x-ui.button>
  </div>

  @if ($errorMessage)
    <div
      class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
      role="alert"
    >
      {{ $errorMessage }}
    </div>
  @endif

  <x-ui.card class="mb-6 p-4" :padding="false">
    <div class="flex flex-col gap-4 sm:flex-row">
      <div class="flex-1">
        <x-ui.input
          id="user-search"
          label="Cari pengguna"
          label-sr-only
          wire:model.live.debounce.300ms="search"
          placeholder="Cari nama atau email..."
        />
      </div>
      <div class="sm:w-52">
        <x-ui.select
          id="role-filter"
          label="Filter peran"
          label-sr-only
          wire:model.live="role"
        >
          <option value="">Semua Peran</option>
          <option value="ADMIN">ADMIN</option>
          <option value="OFFICER">OFFICER</option>
        </x-ui.select>
      </div>
      @if ($search !== "" || $role !== "")
        <x-ui.button
          type="button"
          variant="secondary"
          wire:click="resetFilters"
        >
          Reset
        </x-ui.button>
      @endif
    </div>
  </x-ui.card>

  <x-ui.card :padding="false">
    @if ($users->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.users-group class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">No users found.</p>
      </div>
    @else
      <x-ui.table label="Daftar pengguna">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
        >
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
              <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900">
                {{ $user->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $user->email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <x-ui.badge
                  :variant="$user->role === \App\Enums\UserRole::Admin ? 'primary' : 'neutral'"
                >
                  {{ strtoupper($user->role->value) }}
                </x-ui.badge>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $user->created_at->format("d M Y, H:i") }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <a
                    href="{{ route("users.edit", $user) }}"
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
                    title="{{ auth()->id() === $user->id ? "Akun sendiri tidak dapat dihapus" : "Hapus " . $user->name }}"
                    @disabled(auth()->id() === $user->id)
                  >
                    <x-icons.trash class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </x-ui.table>

      @if ($users->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $users->links() }}
        </div>
      @endif
    @endif
  </x-ui.card>

  <x-delete-modal
    id="delete-user"
    title="Hapus Pengguna"
    :message="'Apakah Anda yakin ingin menghapus pengguna ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
  />
</div>
