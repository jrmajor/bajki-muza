<span class="whitespace-nowrap">
  @if ($loop->first)
    {{ $before }}
  @elseif ($loop->last)
    i
  @endif

  <a href="{{ route('artists.show', $artist) }}" class="inline-flex items-center">
    {{ $name() }}
    <x-appearances :count="$appearances()" size="small"/>
  </a>{{--

--}}@if ($loop->remaining > 1), @endif
</span>
