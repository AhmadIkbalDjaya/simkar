<div>
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">
      Ringkasan status sistem dan aktivitas mutasi kamar terkini.
    </p>
  </div>

  {{-- Statistics Cards --}}
  <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    {{-- Total Narapidana --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Narapidana</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($totalInmates) }}
          </p>
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100"
        >
          <x-icons.users class="h-6 w-6 text-blue-600" />
        </div>
      </div>
    </div>

    {{-- Total Kamar --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Kamar</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($totalRooms) }}
          </p>
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100"
        >
          <x-icons.building-2 class="h-6 w-6 text-emerald-600" />
        </div>
      </div>
    </div>

    {{-- Kapasitas Total --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Kapasitas Total</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($totalCapacity) }}
          </p>
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100"
        >
          <x-icons.building class="h-6 w-6 text-indigo-600" />
        </div>
      </div>
    </div>

    {{-- Penghuni Saat Ini --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Penghuni Saat Ini</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($currentOccupants) }}
          </p>
          @if ($totalCapacity > 0)
            <p class="mt-1 text-xs text-gray-400">
              {{ round(($currentOccupants / $totalCapacity) * 100) }}% terisi
            </p>
          @endif
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100"
        >
          <x-icons.users-group class="h-6 w-6 text-amber-600" />
        </div>
      </div>
    </div>

    {{-- Mutasi Hari Ini --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Mutasi Hari Ini</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($transfersToday) }}
          </p>
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100"
        >
          <x-icons.arrows-right-left class="h-6 w-6 text-purple-600" />
        </div>
      </div>
    </div>

    {{-- Mutasi Bulan Ini --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Mutasi Bulan Ini</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ number_format($transfersThisMonth) }}
          </p>
        </div>
        <div
          class="flex h-12 w-12 items-center justify-center rounded-lg bg-rose-100"
        >
          <x-icons.calendar class="h-6 w-6 text-rose-600" />
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Transfers --}}
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-gray-900">Mutasi Terkini</h2>
    </div>

    @if ($recentTransfers->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">No recent transfers found.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
          >
            <tr>
              <th class="px-6 py-3 font-medium">Narapidana</th>
              <th class="px-6 py-3 font-medium">Dari Kamar</th>
              <th class="px-6 py-3 font-medium">Ke Kamar</th>
              <th class="px-6 py-3 font-medium">Waktu</th>
              <th class="px-6 py-3 font-medium">Petugas</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($recentTransfers as $transfer)
              <tr class="hover:bg-gray-50">
                <td
                  class="px-6 py-4 font-medium whitespace-nowrap text-gray-900"
                >
                  {{ $transfer->inmate->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->roomFrom->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->roomTo->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->transferred_at->format("d M Y H:i") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->officer_name }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
