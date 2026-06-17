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
          <svg
            class="h-6 w-6 text-blue-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"
            />
          </svg>
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
          <svg
            class="h-6 w-6 text-emerald-600"
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
          <svg
            class="h-6 w-6 text-indigo-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
            />
          </svg>
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
          <svg
            class="h-6 w-6 text-amber-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
            />
          </svg>
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
          <svg
            class="h-6 w-6 text-purple-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
            />
          </svg>
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
          <svg
            class="h-6 w-6 text-rose-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"
            />
          </svg>
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
            d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"
          />
        </svg>
        <p class="mt-4 text-sm text-gray-500">No recent transfers found.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
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
                <td class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                  {{ $transfer->inmate->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $transfer->roomFrom->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $transfer->roomTo->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $transfer->transferred_at->format('d M Y H:i') }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
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
