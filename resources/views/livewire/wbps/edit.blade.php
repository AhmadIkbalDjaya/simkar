<div>
  {{-- Header --}}
  <div class="mb-6">
    <a
      href="{{ route("wbps.show", $wbp) }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">
      Edit {{ $wbp->name }}
    </h1>
  </div>

  {{-- Form --}}
  <x-ui.card class="max-w-2xl">
    <form wire:submit="save" class="space-y-5">
      <x-ui.input
        name="form.registration_number"
        label="Nomor Registrasi"
        wire:model="form.registration_number"
      />

      <x-ui.input name="form.name" label="Nama WBP" wire:model="form.name" />

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

      <x-ui.select
        name="form.current_room_id"
        label="Kamar Saat Ini"
        wire:model="form.current_room_id"
      >
        <option value="">Belum ditempatkan</option>
        @foreach ($rooms as $room)
          <option
            value="{{ $room->id }}"
            {{ $room->current_occupancy >= $room->capacity && $room->id !== $wbp->current_room_id ? "disabled" : "" }}
          >
            {{ $room->name }}
            ({{ $room->current_occupancy }}/{{ $room->capacity }})
            {{ $room->current_occupancy >= $room->capacity && $room->id !== $wbp->current_room_id ? "- Penuh" : "" }}
          </option>
        @endforeach
      </x-ui.select>

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
          :href="route('wbps.show', $wbp)"
          variant="secondary"
          wire:navigate
        >
          Batal
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>
</div>
