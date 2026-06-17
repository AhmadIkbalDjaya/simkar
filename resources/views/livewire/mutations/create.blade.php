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
    <h1 class="mt-2 text-2xl font-bold text-gray-900">Buat Mutasi</h1>
  </div>

  {{-- Form --}}
  <div
    class="max-w-2xl rounded-xl border border-gray-200 bg-white p-6 shadow-sm"
  >
    <form
      wire:submit="save"
      class="space-y-5"
      x-data="signaturePad()"
      x-init="init()"
    >
      {{-- WBP --}}
      <div>
        <label
          for="inmate_id"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          WBP
        </label>
        <select
          wire:model.live="inmate_id"
          id="inmate_id"
          class="{{ $errors->has('inmate_id') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Pilih WBP</option>
          @foreach ($inmates as $inmate)
            <option value="{{ $inmate->id }}">
              {{ $inmate->name }} ({{ $inmate->registration_number }})
            </option>
          @endforeach
        </select>
        @error('inmate_id')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Kamar Asal --}}
      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">
          Kamar Asal
        </label>
        <input
          type="text"
          value="{{ $room_from_name ?? 'Pilih WBP terlebih dahulu' }}"
          disabled
          class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-500"
        />
        @error('room_from_id')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Kamar Tujuan --}}
      <div>
        <label
          for="room_to_id"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          Kamar Tujuan
        </label>
        <select
          wire:model="room_to_id"
          id="room_to_id"
          class="{{ $errors->has('room_to_id') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        >
          <option value="">Pilih kamar tujuan</option>
          @foreach ($availableRooms as $room)
            <option value="{{ $room->id }}">
              {{ $room->name }}
              ({{ $room->current_occupancy }}/{{ $room->capacity }})
            </option>
          @endforeach
        </select>
        @error('room_to_id')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Waktu Mutasi --}}
      <div>
        <label
          for="transferred_at"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          Waktu Mutasi
        </label>
        <input
          wire:model="transferred_at"
          id="transferred_at"
          type="datetime-local"
          class="{{ $errors->has('transferred_at') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
        @error('transferred_at')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Nama Petugas --}}
      <div>
        <label
          for="officer_name"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          Nama Petugas
        </label>
        <input
          wire:model="officer_name"
          id="officer_name"
          type="text"
          class="{{ $errors->has('officer_name') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
        />
        @error('officer_name')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Catatan --}}
      <div>
        <label
          for="notes"
          class="mb-1 block text-sm font-medium text-gray-700"
        >
          Catatan
        </label>
        <textarea
          wire:model="notes"
          id="notes"
          rows="3"
          class="{{ $errors->has('notes') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
          placeholder="Catatan tambahan (opsional)"
        ></textarea>
        @error('notes')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Tanda Tangan --}}
      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">
          Tanda Tangan Petugas
        </label>
        <div
          class="{{ $errors->has('officer_signature') ? 'border-red-500' : 'border-gray-300' }} rounded-lg border bg-white"
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
          <span
            x-show="signed"
            x-cloak
            class="text-xs text-emerald-600"
          >
            Tanda tangan tersimpan
          </span>
        </div>
        @error('officer_signature')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex items-center gap-3 pt-2">
        <button
          type="submit"
          @click="syncSignature()"
          class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800 disabled:opacity-50"
          wire:loading.attr="disabled"
        >
          <x-icons.spinner
            wire:loading
            wire:target="save"
            class="mr-2 h-4 w-4 animate-spin"
          />
          Simpan Mutasi
        </button>
        <a
          href="{{ route('mutations.index') }}"
          wire:navigate
          class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
        >
          Batal
        </a>
      </div>
    </form>
  </div>
</div>

@script
  <script>
    Alpine.data('signaturePad', () => ({
      canvas: null,
      ctx: null,
      drawing: false,
      signed: false,

      init() {
        this.canvas = this.$refs.signatureCanvas
        this.ctx = this.canvas.getContext('2d')

        this.resize()

        window.addEventListener('resize', () => this.resize())

        this.canvas.addEventListener('pointerdown', (e) => this.startDrawing(e))
        this.canvas.addEventListener('pointermove', (e) => this.draw(e))
        this.canvas.addEventListener('pointerup', () => this.stopDrawing())
        this.canvas.addEventListener('pointerleave', () => this.stopDrawing())
      },

      resize() {
        const rect = this.canvas.parentElement.getBoundingClientRect()
        const dpr = window.devicePixelRatio || 1
        this.canvas.width = rect.width * dpr
        this.canvas.height = 160 * dpr
        this.canvas.style.width = rect.width + 'px'
        this.canvas.style.height = '160px'
        this.ctx.scale(dpr, dpr)
        this.ctx.strokeStyle = '#1f2937'
        this.ctx.lineWidth = 2
        this.ctx.lineCap = 'round'
        this.ctx.lineJoin = 'round'
      },

      getPos(e) {
        const rect = this.canvas.getBoundingClientRect()
        return {
          x: e.clientX - rect.left,
          y: e.clientY - rect.top,
        }
      },

      startDrawing(e) {
        this.drawing = true
        const pos = this.getPos(e)
        this.ctx.beginPath()
        this.ctx.moveTo(pos.x, pos.y)
      },

      draw(e) {
        if (!this.drawing) return
        const pos = this.getPos(e)
        this.ctx.lineTo(pos.x, pos.y)
        this.ctx.stroke()
        this.signed = true
      },

      stopDrawing() {
        this.drawing = false
      },

      clear() {
        const dpr = window.devicePixelRatio || 1
        this.ctx.clearRect(
          0,
          0,
          this.canvas.width / dpr,
          this.canvas.height / dpr,
        )
        this.signed = false
        $wire.set('officer_signature', '')
      },

      syncSignature() {
        if (this.signed) {
          const data = this.canvas.toDataURL('image/png')
          $wire.set('officer_signature', data)
        }
      },
    }))
  </script>
@endscript
