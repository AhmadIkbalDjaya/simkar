<x-ui.input name="name" label="Nama" wire:model="name" autocomplete="name" />

<x-ui.input
  name="email"
  label="Email"
  type="email"
  wire:model="email"
  autocomplete="email"
/>

<x-ui.input
  name="password"
  :label="'Password '.($editing ? '(opsional)' : '')"
  type="password"
  wire:model="password"
  autocomplete="new-password"
  :hint="$editing ? 'Kosongkan jika password tidak ingin diubah.' : null"
/>

<x-ui.select name="role" label="Peran" wire:model="role">
  <option value="">Pilih peran</option>
  <option value="ADMIN">ADMIN</option>
  <option value="OFFICER">OFFICER</option>
</x-ui.select>
