<?php

use App\Models\Artist;
use Facades\App\Services\Discogs;
use Facades\App\Services\Wikipedia;

use function Pest\Laravel\get;

it('works', function () {
    $artist = Artist::factory()->create();

    Wikipedia::partialMock()
        ->shouldReceive('extract')
        ->andReturn('test');

    Discogs::partialMock()
        ->shouldReceive('photos')
        ->andReturn(collect());

    get("artysci/{$artist->slug}")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent artist')
    ->get('artysci/nonexistent-artist')
    ->assertStatus(404);
