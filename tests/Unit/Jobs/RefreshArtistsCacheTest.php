<?php

use App\Jobs\RefreshArtistsCache;
use App\Models\Artist;
use Facades\App\Services\Discogs;
use Facades\App\Services\FilmPolski;
use Facades\App\Services\Wikipedia;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('can refresh artists cache', function () {
    Artist::factory(48)->sequence(
        [],
        ['discogs' => null, 'filmpolski' => null, 'wikipedia' => null],
    )->create();

    Discogs::shouldReceive('refreshCache')->times(24);
    FilmPolski::shouldReceive('refreshCache')->times(24);
    Wikipedia::shouldReceive('refreshCache')->times(24);

    RefreshArtistsCache::dispatchNow();
});
