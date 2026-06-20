@props([
  "name" => null,
  "label" => null,
  "type" => "text",
  "id" => null,
  "hint" => null,
  "labelSrOnly" => false,
])

@php
  $id = $id ?: ($name ? str_replace([".", "[", "]"], ["-", "-", ""], $name) : null);
  $error = $name ? $errors->first($name) : null;
  $describedBy = collect([$hint ? $id . "-hint" : null, $error ? $id . "-error" : null])
    ->filter()
    ->implode(" ");
@endphp

<div>
  @if ($label)
    <label
      for="{{ $id }}"
      @class(["mb-1.5 block text-sm font-medium text-gray-700", "sr-only" => $labelSrOnly])
    >
      {{ $label }}
    </label>
  @endif

  <input
    id="{{ $id }}"
    name="{{ $name }}"
    type="{{ $type }}"
    @if ($error) aria-invalid="true" @endif
    @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
    {{ $attributes->merge(["class" => ($error ? "border-red-500" : "border-gray-300") . " w-full rounded-lg border bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition-colors placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"]) }}
  />
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
