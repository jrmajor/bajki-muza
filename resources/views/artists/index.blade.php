@push('scripts')
    @livewireStyles
    @livewireScripts
@endpush

<div class="w-full">
    <div class="space-y-3 mb-8 flex flex-col items-center">
        <input type="search" wire:key="search" wire:model.debounce.100ms="search" autocomplete="off" autofocus
            class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-50 focus:outline-none">

        @foreach ($artists as $artist)
            <a href="{{ route('artists.show', $artist) }}" wire:key="{{ $artist->id }}"
                class="w-full h-12 sm:h-14 flex items-center bg-gray-50 rounded-lg shadow-lg overflow-hidden">
                <div class="flex-none bg-gray-400 bg-cover w-12 h-12 sm:w-14 sm:h-14"
                    style="background-image: url(&quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M10.998 8.504C8.682 10.98 7.27 14.744 7.87 19.9l.037.32-.178.266c-.83 1.247-.657 2.119-.304 2.667.412.642 1.144.973 1.576.973h.592l.22.55c1.473 3.681 3.167 6.38 4.932 8.144 1.76 1.76 3.55 2.556 5.256 2.556 1.707 0 3.496-.796 5.256-2.556 1.765-1.765 3.46-4.463 4.932-8.144l.22-.55H31c.432 0 1.164-.331 1.577-.973.352-.548.526-1.42-.305-2.667l-.178-.267.037-.32c.6-5.155-.813-8.92-3.13-11.394C26.672 6.014 23.35 4.75 20 4.75c-3.35 0-6.67 1.264-9.002 3.754zm22.905 11.288c.56-5.444-.96-9.637-3.624-12.484C27.58 4.424 23.775 3 20 3c-3.775 0-7.58 1.424-10.28 4.308-2.664 2.847-4.183 7.04-3.623 12.484-.984 1.647-.878 3.167-.146 4.306.58.9 1.525 1.51 2.432 1.708 1.486 3.584 3.222 6.35 5.123 8.25 1.99 1.99 4.2 3.069 6.494 3.069 2.293 0 4.504-1.079 6.494-3.069 1.9-1.9 3.637-4.666 5.123-8.25.907-.197 1.853-.808 2.431-1.708.733-1.139.84-2.659-.145-4.306z M17 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM26 22a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z M16.882 11.892c-.452 1.88-1.68 4.402-4.79 7.253a.875.875 0 11-1.183-1.29c2.89-2.649 3.911-4.876 4.272-6.372a6.635 6.635 0 00.175-2.086 9.324 9.324 0 01-.01-.175V9.22c-.002-.055-.008-.174.008-.293a.964.964 0 01.129-.368.896.896 0 01.767-.434c.302 0 .505.147.586.215.09.074.148.152.178.192.06.083.108.174.137.228.03.054.054.103.08.151.042.084.085.168.149.282a4.8 4.8 0 00.869 1.14c.816.786 2.327 1.654 5.217 1.543 2.107-.081 3.707.253 4.912.861 1.216.613 1.98 1.479 2.447 2.366.46.875.617 1.75.663 2.392a6.347 6.347 0 01-.01 1.028 2.811 2.811 0 01-.008.07l-.003.022v.008l-.001.003v.001c0 .001 0 .002-.866-.127l.865.129a.875.875 0 01-1.731-.254v-.003l.004-.031c.003-.031.008-.081.011-.148.008-.135.011-.333-.006-.572a4.369 4.369 0 00-.468-1.705c-.309-.588-.819-1.18-1.684-1.616-.876-.442-2.164-.748-4.057-.676-3.314.128-5.303-.88-6.498-2.029a5.494 5.494 0 01-.073-.071c-.024.12-.05.242-.081.368z' fill='%23858c99'/%3E%3C/svg%3E&quot;)">
                    @if ($artist->photo('150'))
                        <img src="{{ $artist->photo('150') }}"
                            loading="lazy"
                            class="w-12 h-12 sm:w-14 sm:h-14 object-cover transition-opacity duration-300 opacity-0">
                    @endif
                </div>
                <div class="flex-grow p-2 pl-3">
                    <span class="flex-shrink-0 font-medium">{{ $artist->name }}</span>
                </div>
                <div class="flex-none pr-4">
                    @if ($artist->appearances > 1)
                        <small class="ml-1.5 h-6 w-6 text-xs inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md">
                            {{ $artist->appearances }}
                        </small>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="w-full flex flex-col items-center ">
        {{ $artists->links() }}
    </div>
</div>
