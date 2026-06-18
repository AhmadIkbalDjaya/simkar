<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? config("app.name") }}</title>

    @vite(["resources/css/app.css", "resources/js/app.js"])

    @livewireStyles
  </head>
  <body class="bg-gray-100 font-sans antialiased">
    <div
      x-data="{ sidebarOpen: false }"
      class="flex h-screen overflow-hidden"
    >
      {{-- Mobile overlay --}}
      <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity duration-300 ease-linear"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300 ease-linear"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
        @click="sidebarOpen = false"
      ></div>

      {{-- Sidebar --}}
      <x-sidebar />

      {{-- Main content --}}
      <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
        <x-topbar />

        <main class="min-w-0 flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
          {{ $slot }}
        </main>
      </div>
    </div>

    @livewireScripts
  </body>
</html>
