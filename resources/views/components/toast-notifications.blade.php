@php
  $initialToast = collect(["success", "error", "warning", "info"])
    ->mapWithKeys(fn ($type) => session()->has($type) ? [$type => session($type)] : [])
    ->map(fn ($message, $type) => ["type" => $type, "message" => $message])
    ->first();
@endphp

<div
  x-data="{
    toasts: [],
    nextId: 0,
    add(detail) {
      const toast = {
        id: ++this.nextId,
        type: ['success', 'error', 'warning', 'info'].includes(detail?.type)
          ? detail.type
          : 'info',
        message: detail?.message ?? '',
      }

      if (! toast.message) return

      this.toasts.push(toast)
      window.setTimeout(() => this.remove(toast.id), detail?.duration ?? 4000)
    },
    remove(id) {
      this.toasts = this.toasts.filter((toast) => toast.id !== id)
    },
  }"
  x-init="if (@js($initialToast)) add(@js($initialToast))"
  x-on:toast.window="add($event.detail)"
  class="pointer-events-none fixed right-4 bottom-4 z-50 flex w-[calc(100%-2rem)] max-w-sm flex-col gap-3 sm:right-6 sm:bottom-6"
  aria-live="polite"
  aria-atomic="true"
>
  <template x-for="toast in toasts" :key="toast.id">
    <div
      x-show="true"
      x-transition:enter="transition duration-200 ease-out"
      x-transition:enter-start="translate-x-4 opacity-0"
      x-transition:enter-end="translate-x-0 opacity-100"
      x-transition:leave="transition duration-150 ease-in"
      x-transition:leave-start="translate-x-0 opacity-100"
      x-transition:leave-end="translate-x-4 opacity-0"
      class="pointer-events-auto flex items-start gap-3 rounded-xl border bg-white px-4 py-2.5 shadow-lg"
      :class="{
        'border-emerald-200': toast.type === 'success',
        'border-red-200': toast.type === 'error',
        'border-amber-200': toast.type === 'warning',
        'border-blue-200': toast.type === 'info',
      }"
      role="status"
    >
      <div
        class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-sm font-bold"
        :class="{
          'bg-emerald-100 text-emerald-700': toast.type === 'success',
          'bg-red-100 text-red-700': toast.type === 'error',
          'bg-amber-100 text-amber-700': toast.type === 'warning',
          'bg-blue-100 text-blue-700': toast.type === 'info',
        }"
        x-text="
          toast.type === 'success'
            ? '\u2713'
            : toast.type === 'error'
              ? '\u00d7'
              : toast.type === 'warning'
                ? '!'
                : 'i'
        "
      ></div>

      <p
        class="min-w-0 flex-1 text-sm leading-6 text-gray-700"
        x-text="toast.message"
      ></p>

      <button
        type="button"
        class="rounded-md p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 focus:ring-2 focus:ring-gray-300 focus:outline-none"
        x-on:click="remove(toast.id)"
        aria-label="Tutup notifikasi"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </template>
</div>
