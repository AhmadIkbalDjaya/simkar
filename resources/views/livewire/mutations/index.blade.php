<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Riwayat Mutasi</h1>
      <p class="mt-1 text-sm text-gray-500">
        Riwayat perpindahan kamar WBP.
      </p>
    </div>
    <a
      href="{{ route('mutations.create') }}"
      wire:navigate
      class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
    >
      <x-icons.plus class="h-4 w-4" />
      Buat Mutasi
    </a>
  </div>

  {{-- Filters --}}
  <div
    class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm"
  >
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <div>
        <input
          wire:model.live.debounce.300ms="search"
          type="text"
          placeholder="Cari nama WBP..."
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
      </div>
      <div>
        <input
          wire:model.live="dateFrom"
          type="date"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
          placeholder="Dari tanggal"
        />
      </div>
      <div>
        <input
          wire:model.live="dateTo"
          type="date"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
          placeholder="Sampai tanggal"
        />
      </div>
      <div>
        <input
          wire:model.live.debounce.300ms="officer"
          type="text"
          placeholder="Cari nama petugas..."
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
      </div>
      <div>
        <select
          wire:model.live="roomFromId"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Semua Kamar Asal</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <select
          wire:model.live="roomToId"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Semua Kamar Tujuan</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    @if ($search || $dateFrom || $dateTo || $officer || $roomFromId || $roomToId)
      <div class="mt-3">
        <button
          wire:click="resetFilters"
          class="text-sm text-gray-500 hover:text-gray-700"
        >
          Reset filter
        </button>
      </div>
    @endif
  </div>

  {{-- Table --}}
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    @if ($mutations->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">
          Tidak ada riwayat mutasi ditemukan.
        </p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-500"
          >
            <tr>
              <th class="px-6 py-3 font-medium">WBP</th>
              <th class="px-6 py-3 font-medium">Kamar Asal</th>
              <th class="px-6 py-3 font-medium">Kamar Tujuan</th>
              <th class="px-6 py-3 font-medium">Waktu</th>
              <th class="px-6 py-3 font-medium">Petugas</th>
              <th class="px-6 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($mutations as $mutation)
              <tr class="hover:bg-gray-50">
                <td
                  class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                >
                  {{ $mutation->inmate->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $mutation->roomFrom->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $mutation->roomTo->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $mutation->transferred_at->format('d M Y H:i') }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $mutation->officer_name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <a
                    href="{{ route('mutations.show', $mutation) }}"
                    wire:navigate
                    class="text-indigo-600 hover:text-indigo-800"
                    title="Detail"
                  >
                    <x-icons.eye class="h-4 w-4" />
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($mutations->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $mutations->links() }}
        </div>
      @endif
    @endif
  </div>
</div>
