@push('scripts')
    @livewireStyles
    @livewireScripts
@endpush

<div class="w-full">
    <div class="space-y-5 mb-8 flex flex-col items-center">
        <input type="search" wire:key="search" wire:model.debounce.500ms="search" autocomplete="off" autofocus
            class="w-full px-4 py-2 rounded-lg shadow-lg overflow-hidden bg-gray-100 focus:outline-none">

        @foreach ($tales as $tale)
            <a href="{{ route('tales.show', $tale) }}" wire:key="{{ $tale->id }}"
                class="w-full h-32 flex items-center bg-gray-100 rounded-lg shadow-lg overflow-hidden">
            <div class="flex-none bg-gray-400 bg-cover w-32 h-32"
                    style="background-image: url(
                        @if ($tale->cover)
                            &quot;{{ $tale->cover_placeholder }}&quot;
                        @else
                            &quot;data:image/svg+xml;utf8,%3Csvg viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill-rule='evenodd' d='M20 34.5c8.008 0 14.5-6.492 14.5-14.5S28.008 5.5 20 5.5 5.5 11.992 5.5 20 11.992 34.5 20 34.5zm0 1.5c8.837 0 16-7.163 16-16S28.837 4 20 4 4 11.163 4 20s7.163 16 16 16z M20 24.5a4.5 4.5 0 100-9 4.5 4.5 0 000 9zm0 1.5a6 6 0 100-12 6 6 0 000 12z M21.25 20a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z M11.345 30.061l4.1-4.746c-.27-.23-.52-.481-.751-.75L9.98 28.623c.418.513.875.994 1.365 1.44zM30.06 11.344a13.34 13.34 0 00-1.436-1.363l-4.048 4.722c.261.225.505.47.73.731l4.754-4.09z' fill='%23858c99'/%3E%3C/svg%3E&quot;
                        @endif
                    )">
                    @if ($tale->cover)
                        <img src="{{ $tale->cover('256') }}"
                            srcset="
                                {{ $tale->cover('128') }} 1x,
                                {{ $tale->cover('192') }} 1.5x,
                                {{ $tale->cover('256') }} 2x"
                            loading="lazy"
                            class="w-32 h-32 object-cover transition-opacity duration-300 opacity-0">
                    @endif
                </div>
                <div class="flex-grow self-stretch flex flex-col justify-between p-4 sm:p-5 sm:pl-6">
                    <div class="text-lg sm:text-xl font-medium leading-tight">
                        {{ $tale->title }}
                    </div>
                    <small>{{ $tale->year }}</small>
                </div>
            </a>
        @endforeach
    </div>

    <div class="w-full flex flex-col items-center ">
        {{ $tales->links() }}
    </div>
</div>
