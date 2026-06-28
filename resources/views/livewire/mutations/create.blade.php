<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
  >
    <div>
      <a
        href="{{ route("mutations.index") }}"
        wire:navigate
        class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700"
      >
        <x-icons.arrow-left class="h-4 w-4" />
        Kembali
      </a>
      <h1 class="mt-2 text-2xl font-bold text-gray-900">Buat Mutasi</h1>
      <p class="mt-1 text-sm text-gray-500">
        Catat perpindahan WBP antar kamar dengan data petugas dan tanda tangan.
      </p>
    </div>
    <x-ui.button
      type="button"
      variant="secondary"
      x-on:click="$dispatch('open-qr-modal', { id: 'general-mutation-qr' })"
    >
      <x-icons.qr-code class="h-4 w-4" />
      QR Input Mutasi
    </x-ui.button>
  </div>

  {{-- Form --}}
  @php
    $inmateOptions = $inmates->map(
      fn ($inmate) => [
        "value" => $inmate->id,
        "label" => $inmate->name . " (" . $inmate->registration_number . ")",
        "search" => $inmate->name . " " . $inmate->registration_number,
      ],
    );
    $roomOptions = $availableRooms->map(
      fn ($room) => [
        "value" => $room->id,
        "label" =>
          $room->name .
          " (" .
          $room->current_occupancy .
          "/" .
          $room->capacity .
          ")" .
          ($room->current_occupancy >= $room->capacity ? " - Penuh" : ""),
        "search" => $room->name,
      ],
    );
    $selectedInmate = $inmates->firstWhere("id", $inmate_id);
    $selectedRoomTo = $availableRooms->firstWhere("id", $room_to_id);
    $targetCapacity = $selectedRoomTo?->capacity ?: 0;
    $targetProjectedOccupancy = $selectedRoomTo
      ? min($selectedRoomTo->current_occupancy + 1, $selectedRoomTo->capacity)
      : 0;
    $targetOccupancyPercent = $targetCapacity ? round(($targetProjectedOccupancy / $targetCapacity) * 100) : 0;
  @endphp

  <form
    wire:submit="save"
    x-data="signaturePad()"
    x-init="init()"
    class="max-w-6xl"
  >
    <x-ui.card
      class="relative z-10"
      :padding="false"
      :overflow-hidden="false"
    >
      @if ($roomQueryError)
        <div
          class="border-b border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 sm:px-6"
          role="alert"
        >
          <div class="flex gap-3">
            <x-icons.exclamation-triangle class="mt-0.5 h-5 w-5 shrink-0" />
            <p>
              {{ $roomQueryError }} Silakan pilih kamar tujuan secara manual.
            </p>
          </div>
        </div>
      @endif

      <div class="grid gap-0 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="space-y-8 p-5 sm:p-6 lg:p-8">
          <section>
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex size-9 items-center justify-center rounded-lg bg-gray-900 text-sm font-semibold text-white"
              >
                1
              </span>
              <div>
                <h2 class="text-base font-semibold text-gray-900">
                  Data Mutasi
                </h2>
                <p class="text-sm text-gray-500">
                  WBP, kamar asal, tujuan, dan waktu perpindahan.
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <x-ui.searchable-select
                  name="inmate_id"
                  label="WBP"
                  :options="$inmateOptions"
                  :selected="$inmate_id"
                  placeholder="Cari nama atau nomor registrasi WBP..."
                  empty-message="WBP tidak ditemukan."
                  wire:model.live="inmate_id"
                  wire:key="inmate-search-{{ $inmate_id ?? 0 }}"
                />
              </div>

              <x-ui.input
                name="room_from_id"
                label="Kamar Asal"
                :value="$room_from_name ?? 'Pilih WBP terlebih dahulu'"
                disabled
              />

              <x-ui.input
                name="transferred_at"
                label="Waktu Mutasi"
                wire:model="transferred_at"
                type="datetime-local"
              />

              <div class="sm:col-span-2">
                <x-ui.searchable-select
                  name="room_to_id"
                  label="Kamar Tujuan"
                  :options="$roomOptions"
                  :selected="$room_to_id"
                  placeholder="Cari kamar tujuan..."
                  empty-message="Kamar tujuan tidak ditemukan."
                  wire:model.live="room_to_id"
                  wire:key="room-to-search-{{ $room_from_id ?? 0 }}-{{ $room_to_id ?? 0 }}"
                />
              </div>
            </div>
          </section>

          <section class="border-t border-gray-200 pt-8">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex size-9 items-center justify-center rounded-lg bg-gray-900 text-sm font-semibold text-white"
              >
                2
              </span>
              <div>
                <h2 class="text-base font-semibold text-gray-900">Petugas</h2>
                <p class="text-sm text-gray-500">
                  Nama petugas, catatan, dan tanda tangan.
                </p>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-5">
              <x-ui.input
                name="officer_name"
                label="Nama Petugas"
                wire:model="officer_name"
              />

              <x-ui.textarea
                name="notes"
                label="Catatan"
                wire:model="notes"
                rows="3"
                placeholder="Catatan tambahan (opsional)"
              />

              <div>
                <div class="mb-1.5 flex items-center justify-between gap-3">
                  <label class="block text-sm font-medium text-gray-700">
                    Tanda Tangan Petugas
                  </label>
                  <span
                    x-show="signed"
                    x-cloak
                    class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700"
                  >
                    Tersimpan
                  </span>
                </div>
                <div
                  class="{{ $errors->has("officer_signature") ? "border-red-500" : "border-gray-300" }} overflow-hidden rounded-lg border bg-white"
                >
                  <canvas
                    x-ref="signatureCanvas"
                    class="block h-40 w-full cursor-crosshair bg-white"
                  ></canvas>
                </div>
                <div class="mt-2 flex items-center justify-between gap-3">
                  <p class="text-xs text-gray-500">
                    Gunakan mouse, stylus, atau layar sentuh.
                  </p>
                  <button
                    type="button"
                    @click="clear()"
                    class="shrink-0 text-sm font-medium text-gray-600 hover:text-gray-900"
                  >
                    Hapus
                  </button>
                </div>
                @error("officer_signature")
                  <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </section>
        </div>

        <aside
          class="border-t border-gray-200 bg-gray-50 p-5 sm:p-6 lg:border-t-0 lg:border-l"
        >
          <div class="lg:sticky lg:top-6">
            <div class="mb-5 flex items-center gap-3">
              <span
                class="flex size-9 items-center justify-center rounded-lg bg-white text-gray-700 ring-1 ring-gray-200"
              >
                <x-icons.arrows-right-left class="h-4 w-4" />
              </span>
              <div>
                <h2 class="text-base font-semibold text-gray-900">Ringkasan</h2>
                <p class="text-sm text-gray-500">Pratinjau data mutasi.</p>
              </div>
            </div>

            <dl class="space-y-5">
              <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">WBP</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900">
                  {{ $selectedInmate?->name ?? "Belum dipilih" }}
                </dd>
                @if ($selectedInmate)
                  <dd class="mt-0.5 text-xs text-gray-500">
                    {{ $selectedInmate->registration_number }}
                  </dd>
                @endif
              </div>

              <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-3">
                <div>
                  <dt class="text-xs font-medium text-gray-500 uppercase">
                    Asal
                  </dt>
                  <dd class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $room_from_name ?? "Belum dipilih" }}
                  </dd>
                </div>
                <div
                  class="mt-5 flex size-8 items-center justify-center rounded-full bg-white text-gray-400 ring-1 ring-gray-200"
                >
                  <x-icons.chevron-right class="h-4 w-4" />
                </div>
                <div>
                  <dt class="text-xs font-medium text-gray-500 uppercase">
                    Tujuan
                  </dt>
                  <dd class="mt-1 text-sm font-semibold text-gray-900">
                    {{ $selectedRoomTo?->name ?? "Belum dipilih" }}
                  </dd>
                </div>
              </div>

              <div>
                <dt class="text-xs font-medium text-gray-500 uppercase">
                  Kapasitas Tujuan
                </dt>

                @if ($selectedRoomTo)
                  <dd class="mt-2">
                    <div
                      class="h-2 overflow-hidden rounded-full bg-gray-200"
                      aria-hidden="true"
                    >
                      <div
                        class="h-full rounded-full bg-gray-900"
                        style="width: {{ $targetOccupancyPercent }}%"
                      ></div>
                    </div>
                    <p class="mt-2 text-sm text-gray-700">
                      {{ $targetProjectedOccupancy }}/{{ $selectedRoomTo->capacity }}
                      terisi setelah mutasi
                    </p>
                  </dd>
                @else
                  <dd class="mt-1 text-sm text-gray-500">
                    Pilih kamar tujuan.
                  </dd>
                @endif
              </div>
            </dl>
          </div>
        </aside>
      </div>

      <div
        class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-end sm:px-6"
      >
        <x-ui.button
          :href="route('mutations.index')"
          variant="secondary"
          wire:navigate
          class="w-full sm:w-auto"
        >
          Batal
        </x-ui.button>
        <x-ui.button
          type="submit"
          @click="syncSignature()"
          wire:loading.attr="disabled"
          class="w-full sm:w-auto"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan Mutasi
        </x-ui.button>
      </div>
    </x-ui.card>
  </form>

  <x-qr-code-modal
    id="general-mutation-qr"
    title="QR Input Mutasi"
    description="Pindai untuk membuka form input mutasi tanpa kamar tujuan terpilih."
    :target-url="route('mutations.create')"
    :image-url="route('mutations.qr.image')"
    :download-url="route('mutations.qr.image', ['download' => 1])"
    :print-url="route('mutations.qr.print')"
    filename="qr-input-mutasi.png"
  />
</div>

@script
  <script>
    Alpine.data('signaturePad', () => ({
      canvas: null,
      ctx: null,
      drawing: false,
      signed: false,

      init() {
        this.canvas = this.$refs.signatureCanvas;
        this.ctx = this.canvas.getContext('2d');

        this.resize();

        window.addEventListener('resize', () => this.resize());

        this.canvas.addEventListener('pointerdown', (e) =>
          this.startDrawing(e),
        );
        this.canvas.addEventListener('pointermove', (e) => this.draw(e));
        this.canvas.addEventListener('pointerup', () => this.stopDrawing());
        this.canvas.addEventListener('pointerleave', () => this.stopDrawing());
      },

      resize() {
        const rect = this.canvas.getBoundingClientRect();
        const dpr = window.devicePixelRatio || 1;
        this.canvas.width = rect.width * dpr;
        this.canvas.height = 160 * dpr;
        this.canvas.style.width = rect.width + 'px';
        this.canvas.style.height = '160px';
        this.ctx.scale(dpr, dpr);
        this.ctx.strokeStyle = '#1f2937';
        this.ctx.lineWidth = 2;
        this.ctx.lineCap = 'round';
        this.ctx.lineJoin = 'round';
      },

      getPos(e) {
        const rect = this.canvas.getBoundingClientRect();
        return {
          x: e.clientX - rect.left,
          y: e.clientY - rect.top,
        };
      },

      startDrawing(e) {
        this.drawing = true;
        const pos = this.getPos(e);
        this.ctx.beginPath();
        this.ctx.moveTo(pos.x, pos.y);
      },

      draw(e) {
        if (!this.drawing) return;
        const pos = this.getPos(e);
        this.ctx.lineTo(pos.x, pos.y);
        this.ctx.stroke();
        this.signed = true;
      },

      stopDrawing() {
        this.drawing = false;
      },

      clear() {
        const dpr = window.devicePixelRatio || 1;
        this.ctx.clearRect(
          0,
          0,
          this.canvas.width / dpr,
          this.canvas.height / dpr,
        );
        this.signed = false;
        $wire.set('officer_signature', '');
      },

      syncSignature() {
        if (this.signed) {
          const data = this.canvas.toDataURL('image/png');
          $wire.set('officer_signature', data);
        }
      },
    }));
  </script>
@endscript
