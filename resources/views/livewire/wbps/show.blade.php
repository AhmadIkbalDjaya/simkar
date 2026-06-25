@php
  $genderLabel =
    $wbp->gender === \App\Enums\GenderType::Male
      ? "Laki-laki"
      : ($wbp->gender === \App\Enums\GenderType::Female
        ? "Perempuan"
        : "-");

  $currentRoom = $wbp->currentRoom?->name ?? "Belum ditempatkan";
  $crimeType = $wbp->crime_type ?: "-";

  $details = [
    "No. Registrasi" => $wbp->registration_number,
    "Nama Lengkap" => $wbp->name,
    "Jenis Kelamin" => $genderLabel,
    "Kamar Saat Ini" => $currentRoom,
  ];

  $dates = [
    "Tanggal Masuk" => $wbp->admission_date?->format("d M Y") ?? "-",
    "Tanggal Penempatan" => $wbp->placement_date?->format("d M Y") ?? "-",
    "Tanggal Bebas" => $wbp->expiration_date?->format("d M Y") ?? "-",
  ];
@endphp

<div class="space-y-6">
  {{-- Header --}}
  <div
    class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
  >
    <div class="min-w-0">
      <a
        href="{{ route("wbps.index") }}"
        wire:navigate
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
      >
        <x-icons.arrow-left class="h-4 w-4" />
        Kembali
      </a>
      <div class="mt-3 flex min-w-0 items-start gap-4">
        <div
          class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-gray-900 text-lg font-semibold text-white"
        >
          {{ strtoupper(substr($wbp->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
          <div class="flex flex-wrap items-center gap-2">
            <h1 class="truncate text-2xl font-bold text-gray-900">
              {{ $wbp->name }}
            </h1>
            <x-ui.badge :variant="$wbp->status->badge()">
              {{ $wbp->status->label() }}
            </x-ui.badge>
          </div>
          <p class="mt-1 text-sm text-gray-500">
            {{ $wbp->registration_number }} - {{ $currentRoom }}
          </p>
        </div>
      </div>
    </div>

    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
      <x-ui.button :href="route('wbps.edit', $wbp)" wire:navigate>
        <x-icons.pencil class="h-4 w-4" />
        Edit WBP
      </x-ui.button>
    @endif
  </div>

  <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Identity --}}
    <x-ui.card class="lg:col-span-2">
      <x-slot:header>
        <div class="flex items-center justify-between gap-3">
          <div>
            <h2 class="text-base font-semibold text-gray-900">Data WBP</h2>
            <p class="mt-1 text-sm text-gray-500">
              Identitas dan penempatan aktif.
            </p>
          </div>
          <x-icons.user class="h-5 w-5 text-gray-400" />
        </div>
      </x-slot>

      <dl class="grid grid-cols-1 gap-x-8 gap-y-5 sm:grid-cols-2">
        @foreach ($details as $label => $value)
          <div class="min-w-0">
            <dt
              class="text-xs font-medium tracking-wide text-gray-500 uppercase"
            >
              {{ $label }}
            </dt>
            <dd
              class="mt-1 text-sm font-medium break-words text-gray-900"
              title="{{ $value }}"
            >
              {{ $value }}
            </dd>
          </div>
        @endforeach

        <div class="min-w-0">
          <dt class="text-xs font-medium tracking-wide text-gray-500 uppercase">
            Status
          </dt>
          <dd class="mt-2">
            <x-ui.badge :variant="$wbp->status->badge()">
              {{ $wbp->status->label() }}
            </x-ui.badge>
          </dd>
        </div>
      </dl>

      <div class="mt-6 border-t border-gray-100 pt-5">
        <p class="text-xs font-medium tracking-wide text-gray-500 uppercase">
          Jenis Kejahatan
        </p>
        <p class="mt-2 text-sm leading-6 break-words text-gray-900">
          {{ $crimeType }}
        </p>
      </div>
    </x-ui.card>

    {{-- Dates --}}
    <x-ui.card>
      <x-slot:header>
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-base font-semibold text-gray-900">Tanggal</h2>
          <x-icons.calendar class="h-5 w-5 text-gray-400" />
        </div>
      </x-slot>

      <dl class="space-y-5">
        @foreach ($dates as $label => $value)
          <div>
            <dt
              class="text-xs font-medium tracking-wide text-gray-500 uppercase"
            >
              {{ $label }}
            </dt>
            <dd class="mt-1 text-sm font-semibold text-gray-900">
              {{ $value }}
            </dd>
          </div>
        @endforeach
      </dl>
    </x-ui.card>
  </div>

  {{-- Transfer History --}}
  <x-ui.card :padding="false">
    <x-slot:header>
      <div
        class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"
      >
        <div>
          <h2 class="text-base font-semibold text-gray-900">Riwayat Mutasi</h2>
          <p class="mt-1 text-sm text-gray-500">
            {{ $transfers->count() }} mutasi terakhir.
          </p>
        </div>
        <x-icons.arrows-right-left class="h-5 w-5 text-gray-400" />
      </div>
    </x-slot>

    @if ($transfers->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada riwayat mutasi.</p>
      </div>
    @else
      <x-ui.table label="Riwayat mutasi WBP">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
        >
          <tr>
            <th class="px-6 py-3 font-medium">Mutasi</th>
            <th class="px-6 py-3 font-medium">Waktu</th>
            <th class="px-6 py-3 font-medium">Petugas</th>
            <th class="px-6 py-3 font-medium">Catatan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach ($transfers as $transfer)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4">
                <div class="flex min-w-64 items-center gap-3 text-sm">
                  <span class="font-medium whitespace-nowrap text-gray-900">
                    {{ $transfer->roomFrom?->name ?? "-" }}
                  </span>
                  <x-icons.chevron-right
                    class="h-4 w-4 shrink-0 text-gray-400"
                  />
                  <span class="font-medium whitespace-nowrap text-gray-900">
                    {{ $transfer->roomTo?->name ?? "-" }}
                  </span>
                </div>
                @if ($transfer->transfer_number)
                  <p class="mt-1 text-xs text-gray-400">
                    {{ $transfer->transfer_number }}
                  </p>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                <div class="font-medium text-gray-900">
                  {{ $transfer->transferred_at->format("d M Y") }}
                </div>
                <div class="mt-1 text-xs text-gray-400">
                  {{ $transfer->transferred_at->format("H:i") }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $transfer->officer_name }}
              </td>
              <td class="max-w-80 px-6 py-4 text-gray-600">
                <div
                  class="line-clamp-2"
                  title="{{ $transfer->notes ?? "-" }}"
                >
                  {{ $transfer->notes ?? "-" }}
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </x-ui.table>
    @endif
  </x-ui.card>
</div>
