<div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
  <div class="w-full max-w-md">
    {{-- Branding --}}
    <div class="mb-8 text-center">
      <div
        class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-gray-900"
      >
        <x-icons.building class="h-8 w-8 text-indigo-400" />
      </div>
      <h1 class="mt-4 text-2xl font-bold text-gray-900">SIMKAR</h1>
      <p class="mt-1 text-sm text-gray-500">Sistem Informasi Mutasi Kamar</p>
    </div>

    {{-- Login Card --}}
    <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">
      <h2 class="mb-6 text-lg font-semibold text-gray-900">Masuk</h2>

      <form wire:submit="login" class="space-y-5">
        {{-- Email --}}
        <div>
          <label
            for="email"
            class="mb-1 block text-sm font-medium text-gray-700"
          >
            Email
          </label>
          <input
            wire:model="email"
            id="email"
            type="email"
            autocomplete="email"
            autofocus
            class="{{ $errors->has("email") ? "border-red-500" : "" }} w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
            placeholder="nama@email.com"
          />
          @error("email")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Password --}}
        <div>
          <label
            for="password"
            class="mb-1 block text-sm font-medium text-gray-700"
          >
            Password
          </label>
          <input
            wire:model="password"
            id="password"
            type="password"
            autocomplete="current-password"
            class="{{ $errors->has("password") ? "border-red-500" : "" }} w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
            placeholder="Masukkan password"
          />
          @error("password")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
          <input
            wire:model="remember"
            id="remember"
            type="checkbox"
            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
          />
          <label for="remember" class="ml-2 text-sm text-gray-600">
            Ingat saya
          </label>
        </div>

        {{-- Submit --}}
        <button
          type="submit"
          class="flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800 disabled:opacity-50"
          wire:loading.attr="disabled"
        >
          <x-icons.spinner wire:loading class="mr-2 h-4 w-4 animate-spin" />
          <span wire:loading.remove>Masuk</span>
          <span wire:loading>Memproses...</span>
        </button>
      </form>
    </div>
  </div>
</div>
