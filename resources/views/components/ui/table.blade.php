@props(["label" => null])

<div {{ $attributes->merge(["class" => "overflow-x-auto"]) }}>
  <table
    class="w-full text-left text-sm"
    @if ($label) aria-label="{{ $label }}" @endif
  >
    {{ $slot }}
  </table>
</div>
