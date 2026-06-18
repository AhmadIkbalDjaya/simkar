<header
  class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-6"
>
  {{-- Left: mobile hamburger + page title --}}
  <div class="flex items-center gap-4">
    <button
      @click="sidebarOpen = true"
      class="text-gray-500 hover:text-gray-700 lg:hidden"
    >
      <x-icons.bars class="h-6 w-6" />
    </button>
    <h2 class="text-lg font-semibold text-gray-800">
      {{ $title ?? "" }}
    </h2>
  </div>

  {{-- Right: user info --}}
  <a
    href="{{ route("profile") }}"
    wire:navigate
    class="flex items-center gap-3 rounded-lg p-1 transition-colors hover:bg-gray-50"
    aria-label="Buka profil"
  >
    <div class="text-right">
      <p class="text-sm font-medium text-gray-700">
        {{ auth()->user()->name ?? "Admin" }}
      </p>
      <p class="text-xs text-gray-500">
        {{ auth()->user() ? strtoupper(auth()->user()->role->value) : "Administrator" }}
      </p>
    </div>
    <div
      class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600"
    >
      {{ strtoupper(substr(auth()->user()->name ?? "A", 0, 1)) }}
    </div>
  </a>
</header>
