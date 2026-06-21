<div>
  {{-- Header --}}
  <div
    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"
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
  <x-ui.card class="max-w-2xl">
    @if ($roomQueryError)
      <div
        class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800"
        role="alert"
      >
        {{ $roomQueryError }} Silakan pilih kamar tujuan secara manual.
      </div>
    @endif

    <form
      wire:submit="save"
      class="space-y-5"
      x-data="signaturePad()"
      x-init="init()"
    >
      {{-- WBP --}}
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
              ($room->current_occupancy >= $room->capacity ? " — Penuh" : ""),
            "search" => $room->name,
          ],
        );
      @endphp

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

      {{-- Kamar Asal --}}
      <x-ui.input
        name="room_from_id"
        label="Kamar Asal"
        :value="$room_from_name ?? 'Pilih WBP terlebih dahulu'"
        disabled
      />

      {{-- Kamar Tujuan --}}
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

      {{-- Waktu Mutasi --}}
      <x-ui.input
        name="transferred_at"
        label="Waktu Mutasi"
        wire:model="transferred_at"
        type="datetime-local"
      />

      {{-- Nama Petugas --}}
      <x-ui.input
        name="officer_name"
        label="Nama Petugas"
        wire:model="officer_name"
      />

      {{-- Catatan --}}
      <x-ui.textarea
        name="notes"
        label="Catatan"
        wire:model="notes"
        rows="3"
        placeholder="Catatan tambahan (opsional)"
      />

      {{-- Tanda Tangan --}}
      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">
          Tanda Tangan Petugas
        </label>
        <div
          class="{{ $errors->has("officer_signature") ? "border-red-500" : "border-gray-300" }} rounded-lg border bg-white"
        >
          <canvas
            x-ref="signatureCanvas"
            class="h-40 w-full cursor-crosshair"
          ></canvas>
        </div>
        <div class="mt-2 flex items-center gap-3">
          <button
            type="button"
            @click="clear()"
            class="text-sm text-gray-500 hover:text-gray-700"
          >
            Hapus tanda tangan
          </button>
          <span x-show="signed" x-cloak class="text-xs text-emerald-600">
            Tanda tangan tersimpan
          </span>
        </div>
        @error("officer_signature")
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex items-center gap-3 pt-2">
        <x-ui.button
          type="submit"
          @click="syncSignature()"
          wire:loading.attr="disabled"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan Mutasi
        </x-ui.button>
        <x-ui.button
          :href="route('mutations.index')"
          variant="secondary"
          wire:navigate
        >
          Batal
        </x-ui.button>
      </div>
    </form>
  </x-ui.card>

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
        const rect = this.canvas.parentElement.getBoundingClientRect();
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
