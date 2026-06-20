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
      <x-ui.button :href="route('wbps.create')" wire:navigate>
        <x-icons.plus class="h-4 w-4" />
        Tambah WBP
      </x-ui.button>
    @endif
  </div>

  {{-- Filters --}}
  <x-ui.card
    class="mb-6 flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row"
    :padding="false"
  >
    <div class="flex-1">
      <x-ui.input
        id="wbp-search"
        label="Cari WBP"
        label-sr-only
        wire:model.live.debounce.300ms="search"
        placeholder="Cari nama atau nomor registrasi..."
      />
    </div>
    <div class="sm:w-44">
      <x-ui.select
        id="wbp-room"
        label="Filter kamar"
        label-sr-only
        wire:model.live="gender"
      >
        <option value="">Semua Gender</option>
        <option value="male">Laki-laki</option>
        <option value="female">Perempuan</option>
      </x-ui.select>
    </div>
    <div class="sm:w-48">
      <x-ui.select
        id="wbp-gender"
        label="Filter jenis kelamin"
        label-sr-only
        wire:model.live="roomId"
      >
        <option value="">Semua Kamar</option>
        @foreach ($rooms as $room)
          <option value="{{ $room->id }}">{{ $room->name }}</option>
        @endforeach
      </x-ui.select>
    </div>
  </x-ui.card>

  {{-- Table --}}
  <x-ui.card :padding="false">
    @if ($wbps->isEmpty())
      <div class="px-6 py-12 text-center">
        <x-icons.users class="mx-auto h-12 w-12 text-gray-300" />
        <p class="mt-4 text-sm text-gray-500">Tidak ada WBP ditemukan.</p>
      </div>
    @else
      <x-ui.table label="Daftar WBP">
        <thead
          class="border-b border-gray-200 bg-gray-50 text-xs tracking-wider text-gray-500 uppercase"
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
              <td class="px-6 py-4 font-medium whitespace-nowrap text-gray-900">
                {{ $wbp->registration_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $wbp->name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $wbp->gender === \App\Enums\GenderType::Male ? "Laki-laki" : "Perempuan" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                {{ $wbp->currentRoom?->name ?? "-" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <a
                    href="{{ route("wbps.show", $wbp) }}"
                    wire:navigate
                    class="text-indigo-600 hover:text-indigo-800"
                    title="Lihat"
                  >
                    <x-icons.eye class="h-4 w-4" />
                  </a>
                  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
                    <a
                      href="{{ route("wbps.edit", $wbp) }}"
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
      </x-ui.table>

      @if ($wbps->hasPages())
        <div class="border-t border-gray-200 px-6 py-4">
          {{ $wbps->links() }}
        </div>
      @endif
    @endif
  </x-ui.card>

  {{-- Delete Modal --}}
  @if (auth()->user()->role === \App\Enums\UserRole::Admin)
    <x-delete-modal
      id="delete-wbp"
      title="Hapus WBP"
      :message="'Apakah Anda yakin ingin menghapus WBP ' . $deleteName . '? Tindakan ini tidak dapat dibatalkan.'"
    />
  @endif
</div>
