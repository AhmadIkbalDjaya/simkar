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
      <a
        href="{{ route("rooms.create") }}"
        wire:navigate
        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
      >
        <x-icons.plus class="h-4 w-4" />
        Tambah Kamar
      </a>
    @endif
  </div>

  {{-- Filters --}}
  <div
    class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row"
  >
    <div class="flex-1">
      <input
        wire:model.live.debounce.300ms="search"
        type="text"
        placeholder="Cari nama kamar..."
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
      />
    </div>
    <div class="sm:w-48">
      <select
        wire:model.live="block"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
      >
        <option value="">Semua Blok</option>
        @foreach ($blocks as $b)
          <option value="{{ $b }}">Blok {{ $b }}</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Table --}}
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    @if ($rooms->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.building-2 class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada kamar ditemukan.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
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
                <td
                  class="px-6 py-4 font-medium whitespace-nowrap text-gray-900"
                >
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
                    <span
                      class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-700"
                    >
                      Tersedia
                    </span>
                  @else
                    <span
                      class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700"
                    >
                      Penuh
                    </span>
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
        </table>
      </div>

      @if ($rooms->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $rooms->links() }}
        </div>
      @endif
    @endif
  </div>

  {{-- Delete Modal --}}
  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
    <x-delete-modal
      id="delete-room"
      title="Hapus Kamar"
      :message="'Apakah Anda yakin ingin menghapus kamar ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
    />
  @endif
</div>
