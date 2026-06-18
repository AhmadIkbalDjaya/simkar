<div>
  <div class="mb-6">
    <a
      href="{{ route("users.index") }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">Tambah Pengguna</h1>
    <p class="mt-1 text-sm text-gray-500">
      Buat akun baru untuk administrator atau petugas.
    </p>
  </div>

  <div
    class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
  >
    <form wire:submit="save" class="space-y-5">
      @include("livewire.users.user-form", ["editing" => false])

      <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row">
        <a
          href="{{ route("users.index") }}"
          wire:navigate
          class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50"
        >
          Batal
        </a>
        <button
          type="submit"
          wire:loading.attr="disabled"
          wire:target="save"
          class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800 disabled:opacity-50"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>
