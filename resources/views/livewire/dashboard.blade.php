<div class="mx-auto w-full max-w-7xl min-w-0 space-y-6 sm:space-y-8">
  {{-- Page heading --}}
  <header
    class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
  >
    <div>
      <div
        class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600"
      >
        <span
          class="flex size-8 items-center justify-center rounded-lg bg-blue-100"
        >
          <x-icons.chart-bar class="size-4" />
        </span>
        Ringkasan SIMKAR
      </div>
      <h1 class="text-2xl font-bold tracking-tight text-gray-950 sm:text-3xl">
        Dashboard
      </h1>
      <p class="mt-1.5 max-w-2xl text-sm leading-6 text-gray-500 sm:text-base">
        Pantau kapasitas kamar dan aktivitas mutasi terkini dalam satu tampilan.
      </p>
    </div>

    <div
      class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-600 shadow-sm"
    >
      <x-icons.calendar class="size-4 text-gray-400" />
      {{ now()->translatedFormat("d F Y") }}
    </div>
  </header>

  {{-- Statistics --}}
  <section aria-labelledby="statistics-heading">
    <h2 id="statistics-heading" class="sr-only">Statistik utama</h2>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3 xl:gap-5">
      <x-stat-card
        label="Total Narapidana"
        :value="number_format($totalInmates)"
        tone="blue"
      >
        <x-slot:icon>
          <x-icons.users class="size-6" />
        </x-slot>
      </x-stat-card>

      <x-stat-card
        label="Total Kamar"
        :value="number_format($totalRooms)"
        tone="emerald"
      >
        <x-slot:icon>
          <x-icons.building-2 class="size-6" />
        </x-slot>
      </x-stat-card>

      <x-stat-card
        label="Kapasitas Total"
        :value="number_format($totalCapacity)"
        tone="indigo"
      >
        <x-slot:icon>
          <x-icons.building class="size-6" />
        </x-slot>
      </x-stat-card>

      <x-stat-card
        label="Penghuni Saat Ini"
        :value="number_format($currentOccupants)"
        :description="$totalCapacity > 0 ? round(($currentOccupants / $totalCapacity) * 100).'% dari kapasitas terisi' : null"
        tone="amber"
      >
        <x-slot:icon>
          <x-icons.users-group class="size-6" />
        </x-slot>
      </x-stat-card>

      <x-stat-card
        label="Mutasi Hari Ini"
        :value="number_format($transfersToday)"
        tone="purple"
      >
        <x-slot:icon>
          <x-icons.arrows-right-left class="size-6" />
        </x-slot>
      </x-stat-card>

      <x-stat-card
        label="Mutasi Bulan Ini"
        :value="number_format($transfersThisMonth)"
        tone="rose"
      >
        <x-slot:icon>
          <x-icons.calendar class="size-6" />
        </x-slot>
      </x-stat-card>
    </div>
  </section>

  {{-- Recent transfers --}}
  <section
    aria-labelledby="recent-transfers-heading"
    class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm"
  >
    <div
      class="flex items-center justify-between gap-4 border-b border-gray-200 px-4 py-4 sm:px-6 sm:py-5"
    >
      <div class="flex min-w-0 items-center gap-3">
        <span
          class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-purple-50 text-purple-600 ring-1 ring-purple-100"
        >
          <x-icons.arrows-right-left class="size-5" />
        </span>
        <div class="min-w-0">
          <h2
            id="recent-transfers-heading"
            class="font-semibold text-gray-950 sm:text-lg"
          >
            Mutasi Terkini
          </h2>
          <p class="mt-0.5 hidden text-sm text-gray-500 sm:block">
            10 aktivitas perpindahan kamar terbaru
          </p>
        </div>
      </div>

      <a
        href="{{ route("mutations.index") }}"
        wire:navigate
        class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-2.5 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50 hover:text-blue-700"
      >
        Lihat semua
        <x-icons.chevron-right class="size-4" />
      </a>
    </div>

    @if ($recentTransfers->isEmpty())
      <div class="px-5 py-14 text-center sm:py-16">
        <div
          class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 ring-8 ring-gray-50"
        >
          <x-icons.arrows-right-left class="size-7" />
        </div>
        <h3 class="mt-5 text-base font-semibold text-gray-900">
          Belum ada mutasi kamar
        </h3>
        <p class="mx-auto mt-1.5 max-w-sm text-sm leading-6 text-gray-500">
          Aktivitas perpindahan narapidana antar kamar akan ditampilkan di sini.
        </p>
        <a
          href="{{ route("mutations.create") }}"
          wire:navigate
          class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
        >
          <x-icons.plus class="size-4" />
          Catat mutasi
        </a>
      </div>
    @else
      {{-- Mobile list --}}
      <div class="divide-y divide-gray-100 xl:hidden">
        @foreach ($recentTransfers as $transfer)
          <a
            href="{{ route("mutations.show", $transfer) }}"
            wire:navigate
            wire:key="mobile-transfer-{{ $transfer->id }}"
            class="block px-4 py-4 transition hover:bg-gray-50"
          >
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="truncate font-semibold text-gray-900">
                  {{ $transfer->inmate->name }}
                </p>
                <p class="mt-1 truncate text-xs text-gray-500">
                  {{ $transfer->officer_name }}
                </p>
              </div>
              <span class="shrink-0 text-xs font-medium text-gray-500">
                {{ $transfer->transferred_at->format("d M, H:i") }}
              </span>
            </div>
            <div class="mt-3 flex items-center gap-2 text-sm">
              <span
                class="min-w-0 truncate rounded-lg bg-gray-100 px-2.5 py-1.5 font-medium text-gray-600"
              >
                {{ $transfer->roomFrom->name }}
              </span>
              <x-icons.chevron-right class="size-4 shrink-0 text-gray-400" />
              <span
                class="min-w-0 truncate rounded-lg bg-blue-50 px-2.5 py-1.5 font-semibold text-blue-700"
              >
                {{ $transfer->roomTo->name }}
              </span>
            </div>
          </a>
        @endforeach
      </div>

      {{-- Tablet and desktop table --}}
      <div class="hidden overflow-x-auto xl:block">
        <table class="w-full min-w-[760px] text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50/80 text-xs font-semibold tracking-wide text-gray-500 uppercase"
          >
            <tr>
              <th scope="col" class="px-6 py-3.5">Narapidana</th>
              <th scope="col" class="px-6 py-3.5">Perpindahan Kamar</th>
              <th scope="col" class="px-6 py-3.5">Waktu</th>
              <th scope="col" class="px-6 py-3.5">Petugas</th>
              <th scope="col" class="w-12 px-4 py-3.5">
                <span class="sr-only">Detail</span>
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($recentTransfers as $transfer)
              <tr
                wire:key="transfer-{{ $transfer->id }}"
                class="group transition-colors hover:bg-blue-50/40"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <span
                      class="flex size-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700"
                    >
                      {{ strtoupper(substr($transfer->inmate->name, 0, 1)) }}
                    </span>
                    <span class="font-semibold whitespace-nowrap text-gray-900">
                      {{ $transfer->inmate->name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center gap-2 whitespace-nowrap">
                    <span
                      class="rounded-lg bg-gray-100 px-2.5 py-1.5 font-medium text-gray-600"
                    >
                      {{ $transfer->roomFrom->name }}
                    </span>
                    <x-icons.chevron-right class="size-4 text-gray-400" />
                    <span
                      class="rounded-lg bg-blue-50 px-2.5 py-1.5 font-semibold text-blue-700"
                    >
                      {{ $transfer->roomTo->name }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  <p class="font-medium text-gray-700">
                    {{ $transfer->transferred_at->format("d M Y") }}
                  </p>
                  <p class="mt-0.5 text-xs text-gray-400">
                    {{ $transfer->transferred_at->format("H:i") }} WITA
                  </p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->officer_name }}
                </td>
                <td class="px-4 py-4 text-right">
                  <a
                    href="{{ route("mutations.show", $transfer) }}"
                    wire:navigate
                    aria-label="Lihat detail mutasi {{ $transfer->inmate->name }}"
                    class="inline-flex size-8 items-center justify-center rounded-lg text-gray-400 transition group-hover:bg-white group-hover:text-blue-600 group-hover:shadow-sm"
                  >
                    <x-icons.chevron-right class="size-4" />
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </section>
</div>
