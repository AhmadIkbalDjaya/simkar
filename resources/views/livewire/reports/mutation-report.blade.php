<div>
  {{-- Header --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Laporan Mutasi</h1>
    <p class="mt-1 text-sm text-gray-500">
      Filter dan ekspor data mutasi kamar.
    </p>
  </div>

  {{-- Filters --}}
  <x-ui.card class="mb-6 p-4" :padding="false">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">
          Dari Tanggal
        </label>
        <input
          wire:model.live="startDate"
          type="date"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
      </div>
      <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">
          Sampai Tanggal
        </label>
        <input
          wire:model.live="endDate"
          type="date"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
      </div>
      <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">
          Petugas
        </label>
        <select
          wire:model.live="officer"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Semua Petugas</option>
          @foreach ($officers as $off)
            <option value="{{ $off }}">{{ $off }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="mb-1 block text-xs font-medium text-gray-500">
          Kamar
        </label>
        <select
          wire:model.live="roomId"
          class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Semua Kamar</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}">{{ $room->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    @if ($startDate || $endDate || $officer || $roomId)
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

  {{-- Export Buttons --}}
  <div class="mb-6 flex flex-wrap gap-3">
    <x-ui.button
      wire:click="exportPdf"
      wire:loading.attr="disabled"
      variant="secondary"
    >
      <x-icons.spinner
        wire:loading
        wire:target="exportPdf"
        class="h-4 w-4 animate-spin"
      />
      <x-icons.document
        wire:loading.remove
        wire:target="exportPdf"
        class="h-4 w-4 text-red-500"
      />
      Export PDF
    </x-ui.button>
    <x-ui.button
      wire:click="exportExcel"
      wire:loading.attr="disabled"
      variant="secondary"
    >
      <x-icons.spinner
        wire:loading
        wire:target="exportExcel"
        class="h-4 w-4 animate-spin"
      />
      <x-icons.document
        wire:loading.remove
        wire:target="exportExcel"
        class="h-4 w-4 text-emerald-500"
      />
      Export Excel
    </x-ui.button>
  </div>

  {{-- Preview Table --}}
  <x-ui.card :padding="false">
    <div class="border-b border-gray-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-gray-900">
        Pratinjau Data ({{ $mutations->total() }} data)
      </h2>
    </div>

    @if ($mutations->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">
          Tidak ada data mutasi ditemukan untuk filter yang dipilih.
        </p>
      </div>
    @else
      <x-ui.table label="Pratinjau laporan mutasi">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
        >
          <tr>
            <th class="px-6 py-3 font-medium">WBP</th>
            <th class="px-6 py-3 font-medium">Kamar Asal</th>
            <th class="px-6 py-3 font-medium">Kamar Tujuan</th>
            <th class="px-6 py-3 font-medium">Waktu</th>
            <th class="px-6 py-3 font-medium">Petugas</th>
            <th class="px-6 py-3 font-medium">Catatan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($mutations as $mutation)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900">
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
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $mutation->officer_name }}
              </td>
              <td class="max-w-xs truncate px-6 py-4 text-gray-600">
                {{ $mutation->notes ?? "-" }}
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
</div>
