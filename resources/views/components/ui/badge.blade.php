@props(["variant" => "neutral"])

@php
  $variants = [
    "neutral" => "bg-gray-100 text-gray-700 ring-gray-200",
    "primary" => "bg-indigo-50 text-indigo-700 ring-indigo-200",
    "success" => "bg-emerald-50 text-emerald-700 ring-emerald-200",
    "danger" => "bg-red-50 text-red-700 ring-red-200",
    "warning" => "bg-amber-50 text-amber-700 ring-amber-200",
  ];
@endphp

<span
  {{ $attributes->merge(["class" => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset " . ($variants[$variant] ?? $variants["neutral"])]) }}
>
  {{ $slot }}
</span>
