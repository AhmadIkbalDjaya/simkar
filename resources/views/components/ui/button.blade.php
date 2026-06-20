@props([
  "href" => null,
  "type" => "button",
  "variant" => "primary",
  "size" => "md",
])

@php
  $variants = [
    "primary" => "border-transparent bg-gray-900 text-white shadow-sm hover:bg-gray-800 focus-visible:ring-gray-900",
    "secondary" => "border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50 focus-visible:ring-indigo-500",
    "danger" => "border-transparent bg-red-600 text-white shadow-sm hover:bg-red-700 focus-visible:ring-red-500",
    "ghost" => "border-transparent bg-transparent text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus-visible:ring-indigo-500",
  ];
  $sizes = [
    "sm" => "gap-1.5 px-3 py-2 text-xs",
    "md" => "gap-2 px-4 py-2.5 text-sm",
    "lg" => "gap-2 px-5 py-3 text-sm",
    "icon" => "size-9 p-2",
  ];
  $classes = "inline-flex items-center justify-center rounded-lg border font-medium transition-colors focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 " . ($variants[$variant] ?? $variants["primary"]) . " " . ($sizes[$size] ?? $sizes["md"]);
@endphp

@if ($href)
  <a href="{{ $href }}" {{ $attributes->merge(["class" => $classes]) }}>
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(["class" => $classes]) }}>
    {{ $slot }}
  </button>
@endif
