<div>
  {{-- Header --}}
  <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Daftar Kamar</h1>
      <p class="mt-1 text-sm text-gray-500">Kelola data kamar dan kapasitas.</p>
    </div>
    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
      <a
        href="{{ route('rooms.create') }}"
        wire:navigate
        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
      >
        <svg
          class="h-4 w-4"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 4.5v15m7.5-7.5h-15"
          />
        </svg>
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
        <svg
          class="mx-auto h-12 w-12 text-gray-300"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"
          />
        </svg>
        <p class="mt-4 text-sm text-gray-500">Tidak ada kamar ditemukan.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-500"
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
                  class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                >
                  {{ $room->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $room->block ?? '-' }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $room->capacity }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $room->current_occupancy }}
                </td>
                <td class="whitespace-nowrap px-6 py-4">
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
                <td class="whitespace-nowrap px-6 py-4">
                  <div class="flex items-center gap-2">
                    <a
                      href="{{ route('rooms.show', $room) }}"
                      wire:navigate
                      class="text-indigo-600 hover:text-indigo-800"
                      title="Lihat"
                    >
                      <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                        />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                      </svg>
                    </a>
                    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                      <a
                        href="{{ route('rooms.edit', $room) }}"
                        wire:navigate
                        class="text-amber-600 hover:text-amber-800"
                        title="Edit"
                      >
                        <svg
                          class="h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                          />
                        </svg>
                      </a>
                      <button
                        wire:click="confirmDelete({{ $room->id }}, '{{ $room->name }}')"
                        class="text-red-600 hover:text-red-800"
                        title="Hapus"
                      >
                        <svg
                          class="h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                          />
                        </svg>
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
