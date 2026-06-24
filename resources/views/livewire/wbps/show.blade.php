<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
  >
    <div>
      <a
        href="{{ route("wbps.index") }}"
        wire:navigate
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
      >
        <x-icons.arrow-left class="h-4 w-4" />
        Kembali
      </a>
      <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $wbp->name }}</h1>
    </div>
    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
      <a
        href="{{ route("wbps.edit", $wbp) }}"
        wire:navigate
        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
      >
        <x-icons.pencil class="h-4 w-4" />
        Edit WBP
      </a>
    @endif
  </div>

  {{-- WBP Info --}}
  <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">No. Registrasi</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->registration_number }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Nama</p>
      <p class="mt-2 text-lg font-bold text-gray-900">{{ $wbp->name }}</p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Jenis Kelamin</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->gender === \App\Enums\GenderType::Male ? "Laki-laki" : "Perempuan" }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Kamar Saat Ini</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->currentRoom?->name ?? "Belum ditempatkan" }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Status</p>
      <div class="mt-2">
        <x-ui.badge :variant="$wbp->status->badge()">
          {{ $wbp->status->label() }}
        </x-ui.badge>
      </div>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Jenis Kejahatan</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->crime_type ?? "-" }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Tanggal Masuk</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->admission_date?->format("d M Y") ?? "-" }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Tanggal Penempatan</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->placement_date?->format("d M Y") ?? "-" }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Tanggal Bebas</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $wbp->expiration_date?->format("d M Y") ?? "-" }}
      </p>
    </div>
  </div>

  {{-- Transfer History --}}
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
      <h2 class="text-lg font-semibold text-gray-900">Riwayat Mutasi</h2>
    </div>

    @if ($transfers->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.arrows-right-left class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada riwayat mutasi.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
          >
            <tr>
              <th class="px-6 py-3 font-medium">Dari Kamar</th>
              <th class="px-6 py-3 font-medium">Ke Kamar</th>
              <th class="px-6 py-3 font-medium">Waktu</th>
              <th class="px-6 py-3 font-medium">Petugas</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($transfers as $transfer)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->roomFrom->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->roomTo->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->transferred_at->format("d M Y H:i") }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  {{ $transfer->officer_name }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
