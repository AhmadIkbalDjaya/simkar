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

<div>
  <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password {{ $editing ? '(opsional)' : '' }}</label>
  <input id="password" wire:model="password" type="password" autocomplete="new-password" class="{{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none" />
  @if ($editing) <p class="mt-1 text-xs text-gray-500">Kosongkan jika password tidak ingin diubah.</p> @endif
  @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
  <label for="role" class="mb-1 block text-sm font-medium text-gray-700">Peran</label>
  <select id="role" wire:model="role" class="{{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }} w-full rounded-lg border px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none">
    <option value="">Pilih peran</option>
    <option value="ADMIN">ADMIN</option>
    <option value="OFFICER">OFFICER</option>
  </select>
  @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
