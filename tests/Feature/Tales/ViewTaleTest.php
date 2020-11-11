<?php

use App\Models\Tale;
use App\Values\Discogs\PhotoCollection as DiscogsPhotoCollection;
use Facades\App\Services\Discogs;
use Facades\App\Services\Wikipedia;
use function Pest\Laravel\get;

it('works', function () {
    $tale = Tale::factory()->create();

    Wikipedia::shouldReceive('extract')
        ->andReturn('test');

    Discogs::shouldReceive('photos')
        ->andReturn(new DiscogsPhotoCollection());

    get("bajki/$tale->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent tale')
    ->get('bajki/nonexistent-tale')
    ->assertStatus(404);
