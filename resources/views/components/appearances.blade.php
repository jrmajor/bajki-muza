@php
  $size ??= 'normal';
@endphp

@if ($count > 1)
  <small class="inline-flex items-center justify-center bg-yellow-300 text-yellow-800 rounded-full shadow-md
    @if ($size === 'small') ml-1.5 w-4.5 h-4.5 text-2xs -mr-1
    @else ml-1.5 w-6 h-6 text-xs
    @endif ">
   {{ $count }}
  </small>
@endif
