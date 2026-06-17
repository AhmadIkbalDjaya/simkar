@props(["id" => "delete-modal", "title" => "Hapus Data", "message" => "Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan."])

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
          <x-icons.exclamation-triangle class="h-5 w-5 text-red-600" />
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
          <x-icons.spinner x-show="loading" class="mr-2 h-4 w-4 animate-spin" />
          Hapus
        </button>
      </div>
    </div>
  </div>
</div>
