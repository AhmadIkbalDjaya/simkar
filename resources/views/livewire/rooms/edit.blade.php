<div>
  {{-- Header --}}
  <div class="mb-6">
    <a
      href="{{ route("rooms.show", $room) }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">
      Edit {{ $room->name }}
    </h1>
  </div>

  {{-- Form --}}
  <x-ui.card class="max-w-2xl">
    <form wire:submit="save" class="space-y-5">
      <x-ui.input name="form.name" label="Nama Kamar" wire:model="form.name" />

      <x-ui.input name="form.block" label="Blok" wire:model="form.block" />

      <x-ui.input
        name="form.capacity"
        label="Kapasitas"
        type="number"
        min="1"
        wire:model="form.capacity"
      />

      <div class="flex items-center gap-3 pt-2">
        <x-ui.button type="submit" wire:loading.attr="disabled">
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan Perubahan
        </x-ui.button>
        <x-ui.button
          :href="route('rooms.show', $room)"
          variant="secondary"
          wire:navigate
        >
          Batal
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
</div>
