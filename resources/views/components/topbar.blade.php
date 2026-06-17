<header
  class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-6"
>
  {{-- Left: mobile hamburger + page title --}}
  <div class="flex items-center gap-4">
    <button
      @click="sidebarOpen = true"
      class="text-gray-500 hover:text-gray-700 lg:hidden"
    >
      <svg
        class="h-6 w-6"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
        />
      </svg>
    </button>
    <h2 class="text-lg font-semibold text-gray-800">
      {{ $title ?? "" }}
    </h2>
  </div>

  {{-- Right: user info --}}
  <div class="flex items-center gap-3">
    <div class="text-right">
      <p class="text-sm font-medium text-gray-700">
        {{ auth()->user()->name ?? "Admin" }}
      </p>
      <p class="text-xs text-gray-500">
        {{ auth()->user()->role ?? "Administrator" }}
      </p>
    </div>
    <div
      class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600"
    >
      {{ strtoupper(substr(auth()->user()->name ?? "A", 0, 1)) }}
    </div>
  </div>
</header>
