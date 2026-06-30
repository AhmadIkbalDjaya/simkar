<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Riwayat Mutasi</h1>
      <p class="mt-1 text-sm text-gray-500">Riwayat perpindahan kamar WBP.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <x-ui.button
        type="button"
        variant="secondary"
        x-on:click="$dispatch('open-qr-modal', { id: 'general-mutation-qr' })"
      >
        <x-icons.qr-code class="h-4 w-4" />
        QR Input Mutasi
      </x-ui.button>
      <x-ui.button :href="route('mutations.create')" wire:navigate>
        <x-icons.plus class="h-4 w-4" />
        Buat Mutasi
      </x-ui.button>
    </div>
  </div>

  {{-- Filters --}}
  <x-ui.card class="mb-6 p-4" :padding="false">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <div>
        <x-ui.input
          id="mutation-search"
          label="Cari WBP"
          label-sr-only
          wire:model.live.debounce.300ms="search"
          placeholder="Cari nama WBP..."
        />
      </div>
      <div>
        <x-ui.input
          id="date-from"
          label="Dari tanggal"
          label-sr-only
          wire:model.live="dateFrom"
          type="date"
          placeholder="Dari tanggal"
        />
      </div>
      <div>
        <x-ui.input
          id="date-to"
          label="Sampai tanggal"
          label-sr-only
          wire:model.live="dateTo"
          type="date"
          placeholder="Sampai tanggal"
        />
      </div>
      <div>
        <x-ui.input
          id="officer"
          label="Cari nama petugas"
          label-sr-only
          wire:model.live.debounce.300ms="officer"
          placeholder="Cari nama petugas..."
        />
      </div>
      <div>
        <x-ui.select
          id="room-from"
          label="Kamar asal"
          label-sr-only
          wire:model.live="roomFromId"
        >
          <option value="">Semua Kamar Asal</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </x-ui.select>
      </div>
      <div>
        <x-ui.select
          id="room-to"
          label="Kamar tujuan"
          label-sr-only
          wire:model.live="roomToId"
        >
          <option value="">Semua Kamar Tujuan</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </x-ui.select>
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
  </x-ui.card>

  {{-- Table --}}
  <x-ui.card :padding="false">
    @if ($mutations->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">
          Tidak ada riwayat mutasi ditemukan.
        </p>
      </div>
    @else
      <x-ui.table label="Riwayat mutasi">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
        >
          <tr>
            <th class="px-6 py-3 font-medium whitespace-nowrap">WBP</th>
            <th class="px-6 py-3 font-medium whitespace-nowrap">Kamar Asal</th>
            <th class="px-6 py-3 font-medium whitespace-nowrap">
              Kamar Tujuan
            </th>
            <th class="px-6 py-3 font-medium whitespace-nowrap">Waktu</th>
            <th class="px-6 py-3 font-medium whitespace-nowrap">Petugas</th>
            <th class="px-6 py-3 font-medium whitespace-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($mutations as $mutation)
            <tr class="hover:bg-gray-50">
              <td
                class="max-w-40 truncate px-6 py-4 font-medium whitespace-nowrap text-gray-900"
                title="{{ $mutation->inmate->name }}"
              >
                {{ $mutation->inmate->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $mutation->roomFrom->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $mutation->roomTo->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $mutation->transferred_at->format("d M Y H:i") }}
              </td>
              <td
                class="max-w-40 truncate px-6 py-4 whitespace-nowrap text-gray-600"
                title="{{ $mutation->officer_name }}"
              >
                {{ $mutation->officer_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <a
                  href="{{ route("mutations.show", $mutation) }}"
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
      </x-ui.table>

      @if ($mutations->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $mutations->links() }}
        </div>
      @endif
    @endif
  </x-ui.card>

  <x-qr-code-modal
    id="general-mutation-qr"
    title="QR Input Mutasi"
    description="Pindai untuk membuka form input mutasi tanpa kamar tujuan terpilih."
    :target-url="route('mutations.create')"
    :image-url="route('mutations.qr.image')"
    :download-url="route('mutations.qr.image', ['download' => 1])"
    :print-url="route('mutations.qr.print')"
    filename="qr-input-mutasi.png"
  />
</div>
