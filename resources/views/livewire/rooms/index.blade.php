<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Daftar Kamar</h1>
      <p class="mt-1 text-sm text-gray-500">Kelola data kamar dan kapasitas.</p>
    </div>
    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
      <x-ui.button :href="route('rooms.create')" wire:navigate>
        <x-icons.plus class="h-4 w-4" />
        Tambah Kamar
      </x-ui.button>
    @endif
  </div>

  {{-- Filters --}}
  <x-ui.card
    class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row"
    :padding="false"
  >
    <div class="flex-1">
      <x-ui.input
        id="room-search"
        label="Cari kamar"
        label-sr-only
        wire:model.live.debounce.300ms="search"
        placeholder="Cari nama kamar..."
      />
    </div>
    <div class="sm:w-48">
      <x-ui.select
        id="room-block"
        label="Filter blok"
        label-sr-only
        wire:model.live="block"
      >
        <option value="">Semua Blok</option>
        @foreach ($blocks as $b)
          <option value="{{ $b }}">Blok {{ $b }}</option>
        @endforeach
      </x-ui.select>
    </div>
  </x-ui.card>

  {{-- Table --}}
  <x-ui.card :padding="false">
    @if ($rooms->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.building-2 class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada kamar ditemukan.</p>
      </div>
    @else
      <x-ui.table label="Daftar kamar">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
        >
          <tr>
            <th class="px-6 py-3 font-medium">Nama</th>
            <th class="px-6 py-3 font-medium">Blok</th>
            <th class="px-6 py-3 font-medium">Kapasitas</th>
            <th class="px-6 py-3 font-medium">Penghuni</th>
            <th class="px-6 py-3 font-medium">Status</th>
            <th class="px-6 py-3 font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($rooms as $room)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900">
                {{ $room->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $room->block ?? "-" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $room->capacity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $room->current_occupancy }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                @if ($room->current_occupancy < $room->capacity)
                  <x-ui.badge variant="success">Tersedia</x-ui.badge>
                @else
                  <x-ui.badge variant="danger">Penuh</x-ui.badge>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <a
                    href="{{ route("rooms.show", $room) }}"
                    wire:navigate
                    class="text-indigo-600 hover:text-indigo-800"
                    title="Lihat"
                  >
                    <x-icons.eye class="h-4 w-4" />
                  </a>
                  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <a
                      href="{{ route("rooms.edit", $room) }}"
                      wire:navigate
                      class="text-amber-600 hover:text-amber-800"
                      title="Edit"
                    >
                      <x-icons.pencil class="h-4 w-4" />
                    </a>
                    <button
                      wire:click="confirmDelete({{ $room->id }}, '{{ $room->name }}')"
                      class="text-red-600 hover:text-red-800"
                      title="Hapus"
                    >
                      <x-icons.trash class="h-4 w-4" />
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </x-ui.table>

      @if ($rooms->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $rooms->links() }}
        </div>
      @endif
    @endif
  </x-ui.card>

  {{-- Delete Modal --}}
  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
    <x-delete-modal
      id="delete-room"
      title="Hapus Kamar"
      :message="'Apakah Anda yakin ingin menghapus kamar ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
    />
  @endif
</div>
