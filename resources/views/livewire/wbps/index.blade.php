<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
  >
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Daftar WBP</h1>
      <p class="mt-1 text-sm text-gray-500">
        Kelola data Warga Binaan Pemasyarakatan.
      </p>
    </div>
    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
      <a
        href="{{ route('wbps.create') }}"
        wire:navigate
        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800"
      >
        <x-icons.plus class="h-4 w-4" />
        Tambah WBP
      </a>
    @endif
  </div>

  {{-- Filters --}}
  <div
    class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row"
  >
    <div class="flex-1">
      <input
        wire:model.live.debounce.300ms="search"
        type="text"
        placeholder="Cari nama atau nomor registrasi..."
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
      />
    </div>
    <div class="sm:w-44">
      <select
        wire:model.live="gender"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
      >
        <option value="">Semua Gender</option>
        <option value="male">Laki-laki</option>
        <option value="female">Perempuan</option>
      </select>
    </div>
    <div class="sm:w-48">
      <select
        wire:model.live="roomId"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
      >
        <option value="">Semua Kamar</option>
        @foreach ($rooms as $room)
          <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Table --}}
  <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
    @if ($wbps->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.users class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada WBP ditemukan.</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
          <thead
            class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-500"
          >
            <tr>
              <th class="px-6 py-3 font-medium">No. Registrasi</th>
              <th class="px-6 py-3 font-medium">Nama</th>
              <th class="px-6 py-3 font-medium">Jenis Kelamin</th>
              <th class="px-6 py-3 font-medium">Kamar</th>
              <th class="px-6 py-3 font-medium">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($wbps as $wbp)
              <tr class="hover:bg-gray-50">
                <td
                  class="whitespace-nowrap px-6 py-4 font-medium text-gray-900"
                >
                  {{ $wbp->registration_number }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $wbp->name }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $wbp->gender === \App\Enums\GenderType::Male ? 'Laki-laki' : 'Perempuan' }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                  {{ $wbp->currentRoom?->name ?? '-' }}
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                  <div class="flex items-center gap-2">
                    <a
                      href="{{ route('wbps.show', $wbp) }}"
                      wire:navigate
                      class="text-indigo-600 hover:text-indigo-800"
                      title="Lihat"
                    >
                      <x-icons.eye class="h-4 w-4" />
                    </a>
                    @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                      <a
                        href="{{ route('wbps.edit', $wbp) }}"
                        wire:navigate
                        class="text-amber-600 hover:text-amber-800"
                        title="Edit"
                      >
                        <x-icons.pencil class="h-4 w-4" />
                      </a>
                      <button
                        wire:click="confirmDelete({{ $wbp->id }}, '{{ $wbp->name }}')"
                        class="text-red-600 hover:text-red-800"
                        title="Hapus"
                      >
                        <x-icons.trash class="h-4 w-4" />
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if ($wbps->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $wbps->links() }}
        </div>
      @endif
    @endif
  </div>

  {{-- Delete Modal --}}
  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
    <x-delete-modal
      id="delete-wbp"
      title="Hapus WBP"
      :message="'Apakah Anda yakin ingin menghapus WBP ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
    />
  @endif
</div>
