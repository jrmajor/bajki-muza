<?php

use App\Artist;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\get;

it('works', function () {
    $artist = factory(Artist::class)->create();

    Cache::shouldReceive('remember')
        ->times(5)
        ->andReturn(
            "<p><b>Tadeusz Bartosik</b> (ur. 15 maja 1925 w Modlnicy, zm. 16 kwietnia 1985 w Warszawie) – polski aktor teatralny, filmowy, telewizyjny, dubbingowy i radiowy oraz śpiewak (bas); także reżyser teatralny i recytator. Zagrał w wielu bajkach muzycznych wydanych przez Polskie Nagrania „Muza”.\n</p>",
            'https://img.discogs.com/-6-bkQjJ59fD9BZBaSdqYclgADI=/222x296/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-1023394-1400334173-7694.jpeg.jpg'
        );

    get("artysci/$artist->slug")
        ->assertOk();
});

it('returns 404 when attempting to view nonexistent artist')
    ->get("artysci/nonexistent-artist")
    ->assertStatus(404);
