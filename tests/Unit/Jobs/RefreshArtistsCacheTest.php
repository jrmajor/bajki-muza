<?php

use App\Jobs\RefreshArtistsCache;
use App\Models\Artist;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;

use function Pest\Laravel\mock;

it('can refresh artists cache', function () {
    Artist::factory(48)->sequence(
        [],
        ['discogs' => null, 'filmpolski' => null, 'wikipedia' => null],
    )->create();

    mock(Discogs::class)->shouldReceive('refreshCache')->times(24);
    mock(FilmPolski::class)->shouldReceive('refreshCache')->times(24);
    mock(Wikipedia::class)->shouldReceive('refreshCache')->times(24);

    RefreshArtistsCache::dispatchSync();
});
