@props([
  "id",
  "title",
  "description",
  "targetUrl",
  "imageUrl",
  "downloadUrl",
  "printUrl",
  "filename",
])

<div
  x-data="{
    open: false,
    targetUrl: @js($targetUrl),
    imageUrl: @js($imageUrl),
    filename: @js($filename),
    notify(type, message) {
      window.dispatchEvent(
        new CustomEvent('toast', { detail: { type, message } }),
      )
    },
    fallbackCopy() {
      const input = document.createElement('textarea')
      input.value = this.targetUrl
      input.style.position = 'fixed'
      input.style.opacity = '0'
      document.body.appendChild(input)
      input.select()
      const copied = document.execCommand('copy')
      input.remove()

      if (! copied) throw new Error('Tautan tidak dapat disalin')
    },
    async copyLink() {
      try {
        if (navigator.clipboard && window.isSecureContext) {
          await navigator.clipboard.writeText(this.targetUrl)
        } else {
          this.fallbackCopy()
        }

        this.notify('success', 'Tautan QR berhasil disalin.')
      } catch (error) {
        this.notify(
          'error',
          'Tautan tidak dapat disalin. Silakan salin secara manual.',
        )
      }
    },
    async shareQr() {
      if (! navigator.share) {
        await this.copyLink()
        return
      }

      try {
        const response = await fetch(this.imageUrl, {
          credentials: 'same-origin',
        })
        if (! response.ok) throw new Error('QR tidak dapat dimuat')

        const blob = await response.blob()
        const file = new File([blob], this.filename, { type: 'image/png' })
        const shareWithFile = {
          title: @js($title),
          text: @js($description),
          url: this.targetUrl,
          files: [file],
        }

        if (navigator.canShare && navigator.canShare({ files: [file] })) {
          await navigator.share(shareWithFile)
        } else {
          await navigator.share({
            title: @js($title),
            text: @js($description),
            url: this.targetUrl,
          })
        }
      } catch (error) {
        if (error.name !== 'AbortError') {
          await this.copyLink()
        }
      }
    },
  }"
  x-on:open-qr-modal.window="if ($event.detail.id === @js($id)) open = true"
  x-show="open"
  x-cloak
  x-on:keydown.escape.window="open = false"
  class="fixed inset-0 z-50 overflow-y-auto"
>
  <div
    x-show="open"
    x-transition:enter="transition-opacity duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50"
    x-on:click="open = false"
  ></div>

  <div class="flex min-h-full items-center justify-center p-4">
    <div
      x-ref="dialog"
      x-show="open"
      x-transition:enter="transition duration-200 ease-out"
      x-transition:enter-start="scale-95 opacity-0"
      x-transition:enter-end="scale-100 opacity-100"
      x-transition:leave="transition duration-150 ease-in"
      x-transition:leave-start="scale-100 opacity-100"
      x-transition:leave-end="scale-95 opacity-0"
      class="relative w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
      x-on:click.stop
      role="dialog"
      aria-modal="true"
      aria-labelledby="{{ $id }}-title"
      aria-describedby="{{ $id }}-description"
      tabindex="-1"
      x-effect="if (open) $nextTick(() => $refs.dialog.focus())"
    >
      <button
        type="button"
        x-on:click="open = false"
        class="absolute top-4 right-4 rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus:ring-2 focus:ring-gray-300 focus:outline-none"
        aria-label="Tutup"
      >
        <span class="text-xl leading-none" aria-hidden="true">&times;</span>
      </button>

      <div class="pr-10">
        <h2 id="{{ $id }}-title" class="text-xl font-semibold text-gray-900">
          {{ $title }}
        </h2>
        <p id="{{ $id }}-description" class="mt-1 text-sm text-gray-500">
          {{ $description }}
        </p>
      </div>

      <div
        class="mx-auto mt-5 max-w-xs rounded-xl border border-gray-200 bg-white p-4"
      >
        <img
          src="{{ $imageUrl }}"
          alt="{{ $title }}"
          class="aspect-square w-full"
          width="512"
          height="512"
        />
      </div>

      <div class="mt-4 rounded-lg bg-gray-50 px-3 py-2">
        <p class="text-xs break-all text-gray-600">{{ $targetUrl }}</p>
      </div>

      <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
        <x-ui.button :href="$downloadUrl" variant="secondary" size="sm">
          <x-icons.download class="h-4 w-4" />
          Download
        </x-ui.button>
        <x-ui.button
          :href="$printUrl"
          target="_blank"
          rel="noopener"
          variant="secondary"
          size="sm"
        >
          <x-icons.printer class="h-4 w-4" />
          Print
        </x-ui.button>
        <x-ui.button
          type="button"
          x-on:click="copyLink()"
          variant="secondary"
          size="sm"
        >
          <x-icons.clipboard class="h-4 w-4" />
          Copy Link
        </x-ui.button>
        <x-ui.button
          type="button"
          x-on:click="shareQr()"
          variant="secondary"
          size="sm"
        >
          <x-icons.share class="h-4 w-4" />
          Bagikan
        </x-ui.button>
      </div>
    </div>
  </div>
</div>
