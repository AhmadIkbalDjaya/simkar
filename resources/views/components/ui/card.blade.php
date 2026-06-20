@props(["padding" => true])

<section
  {{ $attributes->merge(["class" => "overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"]) }}
>
  @isset($header)
    <header class="border-b border-gray-200 px-5 py-4 sm:px-6">
      {{ $header }}
    </header>
  @endisset

  @if ($padding)
    <div class="p-5 sm:p-6">{{ $slot }}</div>
  @else
    {{ $slot }}
  @endif
  @isset($footer)
    <footer class="border-t border-gray-200 px-5 py-4 sm:px-6">
      {{ $footer }}
    </footer>
  @endisset
</section>
