@props(['id' => 'delete-modal', 'title' => 'Hapus Data', 'message' => 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.'])

<div
  x-data="{ open: false, loading: false }"
  x-on:open-delete-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
  x-on:close-delete-modal.window="open = false; loading = false"
  x-show="open"
  x-cloak
  class="fixed inset-0 z-50 overflow-y-auto"
>
  {{-- Backdrop --}}
  <div
    x-show="open"
    x-transition:enter="transition-opacity duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50"
    @click="open = false"
  ></div>

  {{-- Modal --}}
  <div class="flex min-h-full items-center justify-center p-4">
    <div
      x-show="open"
      x-transition:enter="transition duration-200 ease-out"
      x-transition:enter-start="scale-95 opacity-0"
      x-transition:enter-end="scale-100 opacity-100"
      x-transition:leave="transition duration-150 ease-in"
      x-transition:leave-start="scale-100 opacity-100"
      x-transition:leave-end="scale-95 opacity-0"
      class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl"
      @click.stop
    >
      <div class="flex items-start gap-4">
        <div
          class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100"
        >
          <svg
            class="h-5 w-5 text-red-600"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
            />
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
          <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
        </div>
      </div>

      <div class="mt-6 flex justify-end gap-3">
        <button
          type="button"
          @click="open = false"
          class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
        >
          Batal
        </button>
        <button
          type="button"
          @click="loading = true; $wire.call('delete').then(() => { open = false; loading = false; })"
          class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 disabled:opacity-50"
          :disabled="loading"
        >
          <svg
            x-show="loading"
            class="mr-2 h-4 w-4 animate-spin"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
            ></path>
          </svg>
          Hapus
        </button>
      </div>
    </div>
  </div>
</div>
