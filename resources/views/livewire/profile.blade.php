<div class="mx-auto max-w-5xl space-y-6">
  <div>
    <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
    <p class="mt-1 text-sm text-gray-500">Kelola informasi akun dan keamanan password Anda.</p>
  </div>

  <div class="grid gap-6 lg:grid-cols-2 lg:items-start">
    <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b border-gray-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-500">Perbarui nama dan alamat email akun.</p>
      </div>

      <form wire:submit="updateProfile" class="space-y-5 p-6">
        <div>
          <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
          <input id="name" wire:model="name" type="text" autocomplete="name" class="{{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
          @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
          <input id="email" wire:model="email" type="email" autocomplete="email" class="{{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
          @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
          <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60">
            <x-icons.spinner wire:loading wire:target="updateProfile" class="h-4 w-4 animate-spin" />
            Simpan Profil
          </button>
        </div>
      </form>
    </section>

    <section class="rounded-xl border border-gray-200 bg-white shadow-sm">
      <div class="border-b border-gray-200 px-6 py-5">
        <h2 class="text-lg font-semibold text-gray-900">Ubah Password</h2>
        <p class="mt-1 text-sm text-gray-500">Gunakan minimal 8 karakter untuk password baru.</p>
      </div>

      <form wire:submit="updatePassword" class="space-y-5 p-6">
        <div>
          <label for="current-password" class="mb-1 block text-sm font-medium text-gray-700">Password Saat Ini</label>
          <input id="current-password" wire:model="currentPassword" type="password" autocomplete="current-password" class="{{ $errors->has('currentPassword') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
          @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="new-password" class="mb-1 block text-sm font-medium text-gray-700">Password Baru</label>
          <input id="new-password" wire:model="password" type="password" autocomplete="new-password" class="{{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
          @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label for="password-confirmation" class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
          <input id="password-confirmation" wire:model="passwordConfirmation" type="password" autocomplete="new-password" class="{{ $errors->has('passwordConfirmation') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
          @error('passwordConfirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
          <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-60">
            <x-icons.spinner wire:loading wire:target="updatePassword" class="h-4 w-4 animate-spin" />
            Ubah Password
          </button>
        </div>
      </form>
    </section>
  </div>
</div>
