<div>
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-1 text-sm text-gray-500">
      Selamat datang di Sistem Informasi Mutasi Kamar.
    </p>
  </div>

  {{-- Stat Cards --}}
  <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
    {{-- Total Narapidana --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Total Narapidana</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">0</p>
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
          <p class="mt-2 text-3xl font-bold text-gray-900">0</p>
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

    {{-- Kamar Terisi --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-500">Kamar Terisi</p>
          <p class="mt-2 text-3xl font-bold text-gray-900">0</p>
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
              d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
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
          <p class="mt-2 text-3xl font-bold text-gray-900">0</p>
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
  </div>
</div>
