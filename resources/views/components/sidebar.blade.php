<aside
  class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-gray-900 text-gray-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
  {{-- Branding --}}
  <div
    class="flex h-16 shrink-0 items-center gap-3 border-b border-gray-700 px-6"
  >
    <x-icons.building class="h-8 w-8 text-indigo-400" />
    <div>
      <h1 class="text-lg font-semibold tracking-wide text-white">SIMKAR</h1>
      <p class="text-xs text-gray-400">Manajemen Kamar</p>
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
    {{-- Dashboard --}}
    <a
      href="{{ route("dashboard") }}"
      wire:navigate
      class="{{ request()->routeIs("dashboard") ? "bg-gray-800 text-white" : "text-gray-300 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors"
    >
      <x-icons.home class="h-5 w-5" />
      Dashboard
    </a>

    {{-- Master Data --}}
    <div
      x-data="{
        open: {{ request()->routeIs("rooms.*") || request()->routeIs("wbps.*") ? "true" : "false" }},
      }"
    >
      <button
        @click="open = !open"
        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
      >
        <span class="flex items-center gap-3">
          <x-icons.database class="h-5 w-5" />
          Master Data
        </span>
        <x-icons.chevron-right
          class="h-4 w-4 transition-transform"
          x-bind:class="open && 'rotate-90'"
        />
      </button>
      <div
        x-show="open"
        x-transition
        class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-4"
      >
        <a
          href="{{ route('wbps.index') }}"
          wire:navigate
          class="{{ request()->routeIs("wbps.*") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.users class="h-4 w-4" />
          Narapidana
        </a>
        <a
          href="{{ route("rooms.index") }}"
          wire:navigate
          class="{{ request()->routeIs("rooms.*") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.building-2 class="h-4 w-4" />
          Kamar
        </a>
      </div>
    </div>

    {{-- Transfers --}}
    <div
      x-data="{
        open: {{ request()->routeIs("mutations.*") ? "true" : "false" }},
      }"
    >
      <button
        @click="open = !open"
        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
      >
        <span class="flex items-center gap-3">
          <x-icons.arrows-right-left class="h-5 w-5" />
          Mutasi
        </span>
        <x-icons.chevron-right
          class="h-4 w-4 transition-transform"
          x-bind:class="open && 'rotate-90'"
        />
      </button>
      <div
        x-show="open"
        x-transition
        class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-4"
      >
        <a
          href="{{ route('mutations.create') }}"
          wire:navigate
          class="{{ request()->routeIs("mutations.create") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.plus-circle class="h-4 w-4" />
          Buat Mutasi
        </a>
        <a
          href="{{ route('mutations.index') }}"
          wire:navigate
          class="{{ request()->routeIs("mutations.index") || request()->routeIs("mutations.show") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.clock class="h-4 w-4" />
          Riwayat Mutasi
        </a>
      </div>
    </div>

    {{-- Reports --}}
    <div
      x-data="{
        open: {{ request()->routeIs("reports.*") ? "true" : "false" }},
      }"
    >
      <button
        @click="open = !open"
        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
      >
        <span class="flex items-center gap-3">
          <x-icons.chart-bar class="h-5 w-5" />
          Laporan
        </span>
        <x-icons.chevron-right
          class="h-4 w-4 transition-transform"
          x-bind:class="open && 'rotate-90'"
        />
      </button>
      <div
        x-show="open"
        x-transition
        class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-4"
      >
        <a
          href="{{ route('reports.mutations') }}"
          wire:navigate
          class="{{ request()->routeIs("reports.mutations") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.document class="h-4 w-4" />
          Laporan Mutasi
        </a>
      </div>
    </div>

    {{-- Users --}}
    <div x-data="{ open: {{ request()->is("users*") ? "true" : "false" }} }">
      <button
        @click="open = !open"
        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
      >
        <span class="flex items-center gap-3">
          <x-icons.users-group class="h-5 w-5" />
          Pengguna
        </span>
        <x-icons.chevron-right
          class="h-4 w-4 transition-transform"
          x-bind:class="open && 'rotate-90'"
        />
      </button>
      <div
        x-show="open"
        x-transition
        class="mt-1 ml-4 space-y-1 border-l border-gray-700 pl-4"
      >
        <a
          href="/users"
          wire:navigate
          class="{{ request()->is("users*") ? "bg-gray-800 text-white" : "text-gray-400 hover:bg-gray-800 hover:text-white" }} flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors"
        >
          <x-icons.user-settings class="h-4 w-4" />
          Kelola Pengguna
        </a>
      </div>
    </div>
  </nav>

  {{-- Bottom section --}}
  <div class="shrink-0 border-t border-gray-700 px-3 py-4">
    <a
      href="/profile"
      wire:navigate
      class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
    >
      <x-icons.user class="h-5 w-5" />
      Profil
    </a>
    <form method="POST" action="/logout">
      @csrf
      <button
        type="submit"
        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-300 transition-colors hover:bg-gray-800 hover:text-white"
      >
        <x-icons.logout class="h-5 w-5" />
        Logout
      </button>
    </form>
  </div>
</aside>
