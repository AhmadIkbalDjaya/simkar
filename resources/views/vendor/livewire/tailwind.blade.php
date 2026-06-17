@php
  if (! isset($scrollTo)) {
    $scrollTo = "body";
  }

  $scrollIntoViewJsSnippet =
    $scrollTo !== false
      ? <<<JS
         (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
      JS
      : "";
@endphp

<div>
  @if ($paginator->hasPages())
    <nav
      role="navigation"
      aria-label="Pagination Navigation"
      class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between"
    >
      {{-- Info --}}
      <p class="text-sm text-gray-500">
        Menampilkan
        <span class="font-medium text-gray-700">
          {{ $paginator->firstItem() }}
        </span>
        -
        <span class="font-medium text-gray-700">
          {{ $paginator->lastItem() }}
        </span>
        dari
        <span class="font-medium text-gray-700">
          {{ $paginator->total() }}
        </span>
        data
      </p>

      {{-- Page buttons --}}
      <div class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
          <span
            class="inline-flex h-9 w-9 cursor-default items-center justify-center rounded-lg text-gray-300"
          >
            <svg
              class="h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 19.5 8.25 12l7.5-7.5"
              />
            </svg>
          </span>
        @else
          <button
            type="button"
            wire:click="previousPage('{{ $paginator->getPageName() }}')"
            x-on:click="{{ $scrollIntoViewJsSnippet }}"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"
            aria-label="Previous"
          >
            <svg
              class="h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 19.5 8.25 12l7.5-7.5"
              />
            </svg>
          </button>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
          @if (is_string($element))
            <span
              class="inline-flex h-9 w-9 items-center justify-center text-sm text-gray-400"
            >
              {{ $element }}
            </span>
          @endif

          @if (is_array($element))
            @foreach ($element as $page => $url)
              <span
                wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
              >
                @if ($page == $paginator->currentPage())
                  <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gray-900 text-sm font-medium text-white"
                  >
                    {{ $page }}
                  </span>
                @else
                  <button
                    type="button"
                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-sm text-gray-600 transition-colors hover:bg-gray-100 hover:text-gray-900"
                    aria-label="{{ __("Go to page :page", ["page" => $page]) }}"
                  >
                    {{ $page }}
                  </button>
                @endif
              </span>
            @endforeach
          @endif
        @endforeach

        {{-- Next --}}

        @if ($paginator->hasMorePages())
          <button
            type="button"
            wire:click="nextPage('{{ $paginator->getPageName() }}')"
            x-on:click="{{ $scrollIntoViewJsSnippet }}"
            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700"
            aria-label="Next"
          >
            <svg
              class="h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="m8.25 4.5 7.5 7.5-7.5 7.5"
              />
            </svg>
          </button>
        @else
          <span
            class="inline-flex h-9 w-9 cursor-default items-center justify-center rounded-lg text-gray-300"
          >
            <svg
              class="h-4 w-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="2"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="m8.25 4.5 7.5 7.5-7.5 7.5"
              />
            </svg>
          </span>
        @endif
      </div>
    </nav>
  @endif
</div>
