<?php /** @var App\Models\Tale $tale */ ?>

@php
  $text = $tale->creditsFor(CreditType::Text);
  $authors = $tale->creditsFor(CreditType::Author);
@endphp

<div class="flex flex-col">
  @if ($text->isNotEmpty() && $authors->isNotEmpty())
    <div>
      <strong>{{ $text->pluck('credit')->pluck('as')->filter()->first() ?? 'Słowa' }}:</strong>
      @foreach ($text as $lyricist)
        <x-name :artist="$lyricist" :$loop/>
      @endforeach
      @foreach ($authors as $author)
        <x-name :artist="$author" :$loop before="wg." genetivus/>
      @endforeach
    </div>
  @elseif ($text->isNotEmpty())
    @foreach ($text->groupBy(fn ($artist) => $artist->credit->as ?? 'Słowa') as $credit => $lyricists)
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($lyricists as $lyricist)
          <x-name :artist="$lyricist" :$loop/>
        @endforeach
      </div>
    @endforeach
  @elseif ($authors->isNotEmpty())
    @foreach ($authors->groupBy(fn ($artist) => $artist->credit->as ?? 'Autor') as $credit => $authors)
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($authors as $author)
          <x-name :artist="$author" :$loop/>
        @endforeach
      </div>
    @endforeach
  @endif

  @unless ($tale->creditsFor(CreditType::Lyrics)->isEmpty())
    <div>
      <strong>Teksty piosenek:</strong>
      @foreach ($tale->creditsFor(CreditType::Lyrics) as $lyricist)
        <x-name :artist="$lyricist" :$loop/>
      @endforeach
    </div>
  @endif

  @unless ($tale->creditsFor(CreditType::Music)->isEmpty())
    @foreach (
      $tale->creditsFor(CreditType::Music)
          ->groupBy(fn ($artist) => $artist->credit->as ?? 'Muzyka')
      as $credit => $composers
    )
      <div>
        <strong>{{ $credit }}:</strong>
        @foreach ($composers as $composer)
          <x-name :artist="$composer" :$loop/>
        @endforeach
      </div>
    @endforeach
  @endif
</div>
