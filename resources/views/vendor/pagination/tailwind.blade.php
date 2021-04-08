@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Nawigacja" class="w-full text-sm font-medium leading-5">

    <div class="flex justify-around w-full sm:hidden gap-3">
      @if (! $paginator->onFirstPage())
        <a id="mobile-pagination-previous" wire:click="previousPage" rel="prev" aria-label="Poprzednia"
          class="relative inline-flex items-center px-4 py-2 cursor-pointer
          text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md rounded-lg
          hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
          focus:outline-none focus:ring
          transition ease-in-out duration-150">
          &laquo; Poprzednia
        </a>
      @endif

      @if ($paginator->hasMorePages())
        <a id="mobile-pagination-next" wire:click="nextPage" rel="next" aria-label="Następna"
          class="relative inline-flex items-center px-4 py-2 cursor-pointer
          text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md rounded-lg
          hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
          focus:outline-none focus:ring
          transition ease-in-out duration-150">
          Następna &raquo;
        </a>
      @endif
    </div>

    <div class="hidden justify-center w-full sm:flex">
      <span class="inline-flex relative z-0 shadow-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <span aria-disabled="true" aria-label="Poprzednia">
            <span class="relative inline-flex items-center px-2 py-2 cursor-default
              text-gray-400 bg-white dark:text-gray-500 dark:bg-gray-900 shadow-md rounded-l-lg" aria-hidden="true">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </span>
          </span>
        @else
          <a id="pagination-previous" wire:click="previousPage" rel="prev" aria-label="Poprzednia"
            class="relative inline-flex items-center px-2 py-2 cursor-pointer
              text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md rounded-l-lg
              hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
              focus:outline-none focus:ring
              transition ease-in-out duration-150">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
          </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <span aria-disabled="true">
              <span class="relative inline-flex items-center px-4 py-2 -ml-px cursor-default
                  text-gray-400 bg-white dark:text-gray-500 dark:bg-gray-900 shadow-md">{{ $element }}</span>
            </span>
          @endif

          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <span aria-current="page">
                  <span id="pg-current"
                    class="relative inline-flex items-center px-4 py-2 -ml-px cursor-default
                      text-gray-400 bg-white dark:text-gray-500 dark:bg-gray-900 shadow-md">{{ $page }}</span>
                </span>
              @else
                <a id="pg-{{ $page }}" wire:click="gotoPage({{ $page }})" aria-label="Idź do strony {{ $page }}"
                  class="relative inline-flex items-center px-4 py-2 -ml-px cursor-pointer
                    text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md
                    hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
                    focus:outline-none focus:ring
                    transition ease-in-out duration-150">
                  {{ $page }}
                </a>
              @endif
            @endforeach
          @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
          <a id="pagination-next" wire:click="nextPage" rel="next" aria-label="Następna"
            class="relative inline-flex items-center px-2 py-2 -ml-px cursor-pointer
              text-gray-800 bg-white dark:text-gray-200 dark:bg-gray-900 shadow-md rounded-r-lg
              hover:text-gray-900 hover:bg-gray-100 dark:hover:text-white dark:hover:bg-gray-800
              focus:outline-none focus:ring
              transition ease-in-out duration-150">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
          </a>
        @else
          <span aria-disabled="true" aria-label="Następna">
            <span class="relative inline-flex items-center px-2 py-2 -ml-px cursor-default
                text-gray-400 bg-white dark:text-gray-500 dark:bg-gray-900 shadow-md rounded-r-lg" aria-hidden="true">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
              </svg>
            </span>
          </span>
        @endif
      </span>
    </div>

  </nav>
@endif
