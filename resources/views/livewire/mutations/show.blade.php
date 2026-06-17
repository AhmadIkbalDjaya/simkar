<div>
  {{-- Header --}}
  <div class="mb-6">
    <a
      href="{{ route('mutations.index') }}"
      wire:navigate
      class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
    >
      <x-icons.arrow-left class="h-4 w-4" />
      Kembali
    </a>
    <h1 class="mt-2 text-2xl font-bold text-gray-900">Detail Mutasi</h1>
  </div>

  {{-- Transfer Info --}}
  <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">WBP</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->inmate->name }}
      </p>
      <p class="mt-1 text-xs text-gray-400">
        {{ $mutation->inmate->registration_number }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Kamar Asal</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->roomFrom->name }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Kamar Tujuan</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->roomTo->name }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Waktu Mutasi</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->transferred_at->format('d M Y') }}
      </p>
      <p class="mt-1 text-xs text-gray-400">
        {{ $mutation->transferred_at->format('H:i') }} WIB
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Nama Petugas</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->officer_name }}
      </p>
    </div>
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
      <p class="text-sm font-medium text-gray-500">Dibuat Oleh</p>
      <p class="mt-2 text-lg font-bold text-gray-900">
        {{ $mutation->creator?->name ?? '-' }}
      </p>
      <p class="mt-1 text-xs text-gray-400">
        {{ $mutation->created_at->format('d M Y H:i') }}
      </p>
    </div>
  </div>

  {{-- Notes --}}
  @if ($mutation->notes)
    <div
      class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
    >
      <p class="mb-2 text-sm font-medium text-gray-500">Catatan</p>
      <p class="text-sm text-gray-900">{{ $mutation->notes }}</p>
    </div>
  @endif

  {{-- Signature --}}
  <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
    <p class="mb-4 text-sm font-medium text-gray-500">
      Tanda Tangan Petugas
    </p>
    @if ($mutation->officer_signature)
      <div
        class="inline-block rounded-lg border border-gray-200 bg-gray-50 p-4"
      >
        <img
          src="{{ $mutation->officer_signature }}"
          alt="Tanda tangan {{ $mutation->officer_name }}"
          class="h-32"
        />
      </div>
    @else
      <p class="text-sm text-gray-400">Tidak ada tanda tangan.</p>
    @endif
  </div>
</div>
