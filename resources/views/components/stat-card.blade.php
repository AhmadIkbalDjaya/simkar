@props([
  "label",
  "value",
  "description" => null,
  "tone" => "blue",
])

@php
  $tones = [
    "blue" => ["icon" => "bg-blue-50 text-blue-600 ring-blue-100", "accent" => "bg-blue-500"],
    "emerald" => ["icon" => "bg-emerald-50 text-emerald-600 ring-emerald-100", "accent" => "bg-emerald-500"],
    "indigo" => ["icon" => "bg-indigo-50 text-indigo-600 ring-indigo-100", "accent" => "bg-indigo-500"],
    "amber" => ["icon" => "bg-amber-50 text-amber-600 ring-amber-100", "accent" => "bg-amber-500"],
    "purple" => ["icon" => "bg-purple-50 text-purple-600 ring-purple-100", "accent" => "bg-purple-500"],
    "rose" => ["icon" => "bg-rose-50 text-rose-600 ring-rose-100", "accent" => "bg-rose-500"],
  ];
  $colors = $tones[$tone] ?? $tones["blue"];
@endphp

<article
  {{
    $attributes->class([
      "group relative overflow-hidden rounded-2xl border border-gray-200/80 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-md sm:p-6",
    ])
  }}
>
  <div class="{{ $colors["accent"] }} absolute inset-x-0 top-0 h-1"></div>

  <div class="flex items-start justify-between gap-4">
    <div class="min-w-0">
      <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
      <p
        class="mt-2 text-3xl font-bold tracking-tight text-gray-950 sm:text-4xl"
      >
        {{ $value }}
      </p>

      @if ($description)
        <p class="mt-2 text-xs font-medium text-gray-500">
          {{ $description }}
        </p>
      @endif
    </div>

    <div
      class="{{ $colors["icon"] }} flex size-12 shrink-0 items-center justify-center rounded-xl ring-1 transition-transform duration-200 group-hover:scale-105"
    >
      {{ $icon }}
    </div>
  </div>
</article>
