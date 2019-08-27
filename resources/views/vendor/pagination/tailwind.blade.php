@if ($paginator->hasPages())
    <ul class="mt-2" role="navigation">

        @if ($paginator->onFirstPage())
            <li class="inline mr-1 px-2 py-1 text-gray-600">
                <span>&lsaquo;</span>
            </li>
        @else
            <li class="inline mr-1 px-2 py-1">
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="inline mr-1 px-2 py-1 text-gray-600"><span>{{ $element }}</span></li>
            @endif


            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="inline mr-1 px-2 py-1 bg-gray-800 rounded-full"><span>{{ $page }}</span></li>
                    @else
                        <li class="inline mr-1 px-2 py-1"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li class="inline mr-1 px-2 py-1">
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
            </li>
        @else
            <li class="inline mr-1 px-2 py-1 text-gray-600">
                <span>&rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
