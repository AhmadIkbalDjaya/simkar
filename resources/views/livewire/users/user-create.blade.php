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

  <x-ui.card class="max-w-2xl">
    <form wire:submit="save" class="space-y-5">
      @include("livewire.users.user-form", ["editing" => false])

      <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row">
        <x-ui.button
          :href="route('users.index')"
          variant="secondary"
          wire:navigate
        >
          Batal
        </x-ui.button>
        <x-ui.button
          type="submit"
          wire:loading.attr="disabled"
          wire:target="save"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
</div>
