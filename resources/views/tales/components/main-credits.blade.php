<?php /** @var App\Models\Tale $tale */ ?>

@php
  $text = $tale->creditsFor(CreditType::text());
  $authors = $tale->creditsFor(CreditType::author());
@endphp

<div class="flex flex-col">
  @if ($text->isNotEmpty() && $authors->isNotEmpty())
    <div>
      <strong>{{ $text->pluck('credit')->pluck('as')->filter()->first() ?? 'Słowa' }}:</strong>
      @foreach ($text as $lyricist)
        <a href="{{ route('artists.show', $lyricist) }}" class="inline-flex items-center">
          {{ $lyricist->name }}
          <x-appearances :count="$lyricist->appearances" size="small"/>
        </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
      @endforeach
      wg.
      @foreach ($authors as $author)
        <a href="{{ route('artists.show', $author) }}" class="inline-flex items-center">
          {{ $author->genetivus ?? $author->name }}
          <x-appearances :count="$author->appearances" size="small"/>
        </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
      @endforeach
    </div>
  @elseif ($text->isNotEmpty())
    @foreach ($text->groupBy(fn ($artist) => $artist->credit->as ?? 'Słowa') as $credit => $lyricists)
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($lyricists as $lyricist)
          <a href="{{ route('artists.show', $lyricist) }}" class="inline-flex items-center">
            {{ $lyricist->name }}
            <x-appearances :count="$lyricist->appearances" size="small"/>
          </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
        @endforeach
      </div>
    @endforeach
  @elseif ($authors->isNotEmpty())
    @foreach ($text->groupBy(fn ($artist) => $artist->credit->as ?? 'Autor') as $credit => $authors)
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($authors as $author)
          <a href="{{ route('artists.show', $author) }}" class="inline-flex items-center">
            {{ $author->name }}
            <x-appearances :count="$author->appearances" size="small"/>
          </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
        @endforeach
      </div>
    @endforeach
  @endif

  @unless ($tale->creditsFor(CreditType::lyrics())->isEmpty())
    <div>
      <strong>Teksty piosenek:</strong>
      @foreach ($tale->creditsFor(CreditType::lyrics()) as $lyricist)
        <a href="{{ route('artists.show', $lyricist) }}" class="inline-flex items-center">
          {{ $lyricist->name }}
          <x-appearances :count="$lyricist->appearances" size="small"/>
        </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
      @endforeach
    </div>
  @endif

  @unless ($tale->creditsFor(CreditType::music())->isEmpty())
    @foreach (
      $tale->creditsFor(CreditType::music())
          ->groupBy(fn ($artist) => $artist->credit->as ?? 'Muzyka')
      as $credit => $composers
    )
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($composers as $composer)
          <a href="{{ route('artists.show', $composer) }}" class="inline-flex items-center">
            {{ $composer->name }}
            <x-appearances :count="$composer->appearances" size="small"/>
          </a>@if ($loop->remaining > 1), @elseif ($loop->remaining > 0) i @endif
        @endforeach
      </div>
    @endforeach
  @endif
</div>
