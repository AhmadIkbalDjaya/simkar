<div>
  {{-- Header --}}
  <div class="mb-6">
    <a
      href="{{ route("wbps.index") }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">Tambah WBP</h1>
  </div>

  {{-- Form --}}
  <x-ui.card class="relative z-10 max-w-2xl" :overflow-hidden="false">
    <form wire:submit="save" class="space-y-5">
      <x-ui.input
        name="form.registration_number"
        label="Nomor Registrasi"
        wire:model="form.registration_number"
        placeholder="Contoh: NPI-00001"
      />

      <x-ui.input
        name="form.name"
        label="Nama WBP"
        wire:model="form.name"
        placeholder="Nama lengkap"
      />

      <x-ui.select
        name="form.gender"
        label="Jenis Kelamin"
        wire:model="form.gender"
      >
        <option value="">Pilih jenis kelamin</option>
        <option value="male">Laki-laki</option>
        <option value="female">Perempuan</option>
      </x-ui.select>

      <x-ui.input
        name="form.crime_type"
        label="Jenis Kejahatan"
        wire:model="form.crime_type"
        placeholder="Contoh: Pencurian"
      />

      <x-ui.select name="form.status" label="Status" wire:model="form.status">
        @foreach (\App\Enums\InmateStatus::cases() as $status)
          <option value="{{ $status->value }}">{{ $status->label() }}</option>
        @endforeach
      </x-ui.select>

      <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-ui.input
          name="form.admission_date"
          label="Tanggal Masuk"
          type="date"
          wire:model="form.admission_date"
        />
        <x-ui.input
          name="form.placement_date"
          label="Tanggal Penempatan"
          type="date"
          wire:model="form.placement_date"
        />
        <x-ui.input
          name="form.expiration_date"
          label="Tanggal Bebas"
          type="date"
          wire:model="form.expiration_date"
        />
      </div>

      @php
        $roomOptions = $rooms->map(
          fn ($room) => [
            "value" => $room->id,
            "label" =>
              $room->name .
              " (" .
              $room->current_occupancy .
              "/" .
              $room->capacity .
              ")" .
              ($room->current_occupancy >= $room->capacity ? " — Penuh" : ""),
            "search" => $room->name,
            "disabled" => $room->current_occupancy >= $room->capacity,
          ],
        );
      @endphp

      <x-ui.searchable-select
        name="form.current_room_id"
        label="Kamar Saat Ini"
        :options="$roomOptions"
        :selected="$form->current_room_id"
        placeholder="Cari kamar saat ini..."
        empty-message="Kamar tidak ditemukan."
        wire:model.live="form.current_room_id"
        wire:key="current-room-search-{{ $form->current_room_id ?? 0 }}"
      />

      <div class="flex items-center gap-3 pt-2">
        <x-ui.button type="submit" wire:loading.attr="disabled">
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan
        </x-ui.button>
        <x-ui.button
          :href="route('wbps.index')"
          variant="secondary"
          wire:navigate
        >
          Batal
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
</div>
