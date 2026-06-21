@props([
  "name",
  "label" => null,
  "id" => null,
  "options" => [],
  "selected" => null,
  "emptyValue" => null,
  "placeholder" => "Cari pilihan...",
  "emptyMessage" => "Pilihan tidak ditemukan.",
  "hint" => null,
  "labelSrOnly" => false,
])

@php
  $id = $id ?: str_replace([".", "[", "]"], ["-", "-", ""], $name);
  $error = $errors->first($name);
  $model = $attributes->wire("model")->value();
  $rootAttributes = $attributes->whereDoesntStartWith("wire:model");
  $describedBy = collect([$hint ? $id . "-hint" : null, $error ? $id . "-error" : null])
    ->filter()
    ->implode(" ");
  $normalizedOptions = collect($options)
    ->map(
      fn ($option) => [
        "value" => (string) data_get($option, "value", data_get($option, "id")),
        "label" => (string) data_get($option, "label"),
        "search" => (string) data_get($option, "search", data_get($option, "label")),
        "disabled" => (bool) data_get($option, "disabled", false),
      ],
    )
    ->values();
@endphp

<div
  x-data="searchableSelect"
  x-init="setup()"
  data-id="{{ $id }}"
  data-model="{{ $model }}"
  data-options="{{ $normalizedOptions->toJson() }}"
  data-selected="{{ $selected }}"
  data-empty-value="{{ json_encode($emptyValue) }}"
  @click.outside="close()"
  @keydown.escape.prevent.stop="close()"
  {{ $rootAttributes->class("relative") }}
>
  @if ($label)
    <label
      id="{{ $id }}-label"
      for="{{ $id }}-search"
      @class(["mb-1.5 block text-sm font-medium text-gray-700", "sr-only" => $labelSrOnly])
    >
      {{ $label }}
    </label>
  @endif

  <div class="relative">
    <input
      id="{{ $id }}-search"
      type="text"
      x-model="query"
      x-ref="searchInput"
      role="combobox"
      aria-autocomplete="list"
      aria-controls="{{ $id }}-options"
      @if ($label) aria-labelledby="{{ $id }}-label" @endif
      :aria-expanded="open"
      :aria-activedescendant="activeOptionId"
      @if ($error) aria-invalid="true" @endif
      @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
      autocomplete="off"
      placeholder="{{ $placeholder }}"
      @focus="openDropdown()"
      @input="filter()"
      @keydown.arrow-down.prevent="move(1)"
      @keydown.arrow-up.prevent="move(-1)"
      @keydown.enter.prevent="selectActive()"
      @keydown.tab="close()"
      class="{{ $error ? "border-red-500" : "border-gray-300" }} w-full rounded-lg border bg-white py-2.5 pr-20 pl-4 text-sm text-gray-900 shadow-sm transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none"
    />

    <div class="absolute inset-y-0 right-0 flex items-center gap-1 pr-2">
      <button
        x-show="selectedValue"
        x-cloak
        type="button"
        aria-label="Hapus pilihan {{ $label ?: $name }}"
        @click="clearSelection()"
        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
      >
        <x-icons.x class="h-3 w-3" />
      </button>
      <button
        type="button"
        aria-label="Buka pilihan {{ $label ?: $name }}"
        @click="toggle()"
        class="rounded p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600"
      >
        <svg
          class="h-4 w-4 transition-transform"
          :class="{ 'rotate-180': open }"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
        >
          <path
            fill-rule="evenodd"
            d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
    </div>
  </div>

  <div
    x-show="open"
    x-cloak
    x-transition.opacity.duration.100ms
    class="absolute z-20 mt-1 max-h-60 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 shadow-lg"
  >
    <ul
      id="{{ $id }}-options"
      role="listbox"
      @if ($label) aria-labelledby="{{ $id }}-label" @endif
    >
      <template
        x-for="(option, index) in filteredOptions"
        :key="option.value"
      >
        <li
          :id="optionId(option)"
          role="option"
          :aria-selected="selectedValue === option.value"
          :aria-disabled="option.disabled"
          @mouseenter="activeIndex = index"
          @mousedown.prevent
          @click="select(option)"
          class="cursor-pointer px-4 py-2.5 text-sm text-gray-900"
          :class="{
            'bg-indigo-50 text-indigo-700': activeIndex === index,
            'font-medium': selectedValue === option.value,
            'cursor-not-allowed text-gray-400': option.disabled,
          }"
        >
          <span x-text="option.label"></span>
        </li>
      </template>
    </ul>

    <p
      x-show="filteredOptions.length === 0"
      class="px-4 py-3 text-sm text-gray-500"
    >
      {{ $emptyMessage }}
    </p>
  </div>

  @if ($hint)
    <p id="{{ $id }}-hint" class="mt-1.5 text-xs text-gray-500">
      {{ $hint }}
    </p>
  @endif

  @if ($error)
    <p id="{{ $id }}-error" class="mt-1.5 text-sm text-red-600">
      {{ $error }}
    </p>
  @endif
</div>
