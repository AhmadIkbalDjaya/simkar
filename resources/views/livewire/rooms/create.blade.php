<div>
  {{-- Header --}}
  <div class="mb-6">
    <a
      href="{{ route("rooms.index") }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">Tambah Kamar</h1>
  </div>

  {{-- Form --}}
  <div
    class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
  >
    <form wire:submit="save" class="space-y-5">
      <div>
        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
          Nama Kamar
        </label>
        <input
          wire:model="form.name"
          id="name"
          type="text"
          class="{{ $errors->has("form.name") ? "border-red-500" : "border-gray-300" }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
          placeholder="Contoh: Kamar A1"
        />
        @error("form.name")
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label for="block" class="mb-1 block text-sm font-medium text-gray-700">
          Blok
        </label>
        <input
          wire:model="form.block"
          id="block"
          type="text"
          class="{{ $errors->has("form.block") ? "border-red-500" : "border-gray-300" }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
          placeholder="Contoh: A"
        />
        @error("form.block")
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label
          for="capacity"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          Kapasitas
        </label>
        <input
          wire:model="form.capacity"
          id="capacity"
          type="number"
          min="1"
          class="{{ $errors->has("form.capacity") ? "border-red-500" : "border-gray-300" }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
        @error("form.capacity")
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button
          type="submit"
          class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800 disabled:opacity-50"
          wire:loading.attr="disabled"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan
        </button>
        <a
          href="{{ route("rooms.index") }}"
          wire:navigate
          class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
        >
          Batal
        </a>
      </div>
    </form>
  </div>
</div>
