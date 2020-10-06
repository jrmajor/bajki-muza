<?php

use App\Models\Artist;
use App\Models\Tale;
use Facades\App\Services\Discogs;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

it('casts discogs and filmpolski ids to integers', function () {
    $artist = Artist::factory()->create([
        'discogs' => '1023394',
        'filmpolski' => '116251',
    ]);

    expect($artist->discogs)->toBe(1023394)
        ->and($artist->discogs)->not->toBe('1023394');

    expect($artist->filmpolski)->toBe(116251)
        ->and($artist->filmpolski)->not->toBe('116251');
});

it('generates slug when created', function () {
    $artist = Artist::create(['name' => 'Tadeusz Włudarski']);

    expect($artist->slug)->toBe('tadeusz-wludarski');

    $artist = new Artist;
    $artist->name = 'Zofia Rysiówna';

    expect($artist->slug)->toBeNull();

    $artist->save();

    expect($artist->slug)->toBe('zofia-rysiowna');
});

it('regenerates slug when updated', function () {
    $artist = Artist::create(['name' => 'Tadeusz Włudarski']);

    expect($artist->slug)->toBe('tadeusz-wludarski');

    $artist->name = 'Zofia Rysiówna';

    $artist->save();

    expect($artist->slug)->toBe('zofia-rysiowna');

    $artist->fill(['name' => 'Andrzej Stockinger'])->save() ;

    expect($artist->slug)->toBe('andrzej-stockinger');
});

it('can generate discogs url', function () {
    $artist = Artist::factory()
        ->make(['discogs' => 518243]);

    expect($artist->discogs_url)
        ->toBe('https://www.discogs.com/artist/518243');

    $artist = Artist::factory()->make();

    expect($artist->discogs_url)
        ->toBe("https://www.discogs.com/artist/$artist->discogs");
});

it('can generate filmpolski url', function () {
    $artist = Artist::factory()
        ->make(['filmpolski' => 112891]);

    expect($artist->filmpolski_url)
        ->toBe('http://www.filmpolski.pl/fp/index.php?osoba=112891');

    $artist = Artist::factory()->make();

    expect($artist->filmpolski_url)
        ->toBe("http://www.filmpolski.pl/fp/index.php?osoba=$artist->filmpolski");
});

it('can generate wikipedia url', function () {
    $artist = Artist::factory()
        ->make(['wikipedia' => 'Joanna_Sobieska']);

    expect($artist->wikipedia_url)
        ->toBe('https://pl.wikipedia.org/wiki/Joanna_Sobieska');

    $artist = Artist::factory()->make();

    expect($artist->wikipedia_url)
        ->toBe("https://pl.wikipedia.org/wiki/$artist->wikipedia");
});

it('does not generate nonexistent urls', function () {
    $artist = Artist::factory()->make([
        'discogs' => null,
        'filmpolski' => null,
        'wikipedia' => null,
    ]);

    expect($artist->discogs_url)->toBeNull()
        ->and($artist->filmpolski_url)->toBeNull()
        ->and($artist->wikipedia_url)->toBeNull();
});

it('can get extract from wikipedia', function () {
    $extract = "<p><b>Piotr Fronczewski</b> (ur. 8 czerwca 1946 w Łodzi) – polski aktor.\n</p>";

    $artist = Artist::factory()
        ->create(['wikipedia' => 'Piotr_Fronczewski']);

    Http::fake([
        'pl.wikipedia.org/*' => Http::response([
            'query' => ['pages' => [3462 => ['extract' => $extract]]]
        ], 200),
    ]);

    expect($artist->wikipedia_extract)->toBe($extract);

    Http::assertSent(
        fn ($request) => $request->url() === 'https://pl.wikipedia.org/w/api.php?action=query&titles=Piotr_Fronczewski&prop=extracts&exintro=1&redirects=1&format=json'
    );
});

it('caches wikipedia extract', function () {
    Http::fake();

    $extract = "<p><b>Piotr Fronczewski</b> (ur. 8 czerwca 1946 w Łodzi) – polski aktor.\n</p>";

    $artist = Artist::factory()
        ->create(['wikipedia' => 'Piotr_Fronczewski']);

    Cache::shouldReceive('remember')
        ->once()
        ->with(
            "artist-$artist->id-wikipedia-extract",
            Carbon\CarbonInterval::class,
            \Closure::class
        )->andReturn($extract);

    expect($artist->wikipedia_extract)->toBe($extract);

    Http::assertSentCount(0);
});

it('does not query wikipedia when no id is set', function () {
    $artist = Artist::factory()
        ->create(['wikipedia' => null]);

    expect($artist->wikipedia_extract)->toBeNull();
});

it('can get photos from discogs', function () {
    $images = [
        [
            'type' => 'primary',
            'uri' => 'test',
            'resource_url' => 'test',
            'uri150' => 'test150',
            'width' => 561,
            'height' => 800,
        ],
    ];

    $artist = Artist::factory()
        ->create(['discogs' => '602473']);

    Discogs::shouldReceive('photos')
        ->with(602473)
        ->andReturn($images);

    expect($artist->discogsPhotos())->toBe($images);
});

it('does not query discogs when no id is set', function () {
    $artist = Artist::factory()
        ->create(['discogs' => null]);

    expect($artist->discogsPhotos())->toBe([]);
});

it('can get photo from discogs', function () {
    $images = [
        [
            'type' => 'primary',
            'uri' => 'test',
            'resource_url' => 'test',
            'uri150' => 'test150',
            'width' => 561,
            'height' => 800,
        ],
    ];

    $artist = Artist::factory()
        ->create(['discogs' => '602473']);

    Discogs::shouldReceive('photos')
        ->with(602473)
        ->andReturn($images);

    expect($artist->photo())->toBe('test')
        ->and($artist->photo('150'))->toBe('test150');
});

it('can get its appearances as director', function () {
    $artist = Artist::factory()->create();

    expect($artist->asDirector())->toBeInstanceOf(HasMany::class);

    Tale::factory()->count(5)->create(['director_id' => $artist->id]);

    expect($artist->asDirector)->toHaveCount(5);

    expect($artist->asDirector->first())->toBeInstanceOf(Tale::class)
        ->and($artist->is($artist->asDirector->first()->director))->toBeTrue();
});

it('can get its appearances as lyricist', function () {
    $artist = Artist::factory()->create();

    expect($artist->asLyricist())->toBeInstanceOf(BelongsToMany::class);

    $artist->asLyricist()->attach($tales = [
        Tale::factory()->create(['year' => 1978])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'b'])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'a'])->id,
    ]);

    expect($artist->asLyricist)->toHaveCount(3);

    expect($artist->asLyricist->get(2)->id)->toBe($tales[0]);
    expect($artist->asLyricist->get(1)->id)->toBe($tales[1]);
    expect($artist->asLyricist->get(0)->id)->toBe($tales[2]);
});

it('can get its appearances as composer', function () {
    $artist = Artist::factory()->create();

    expect($artist->asComposer())->toBeInstanceOf(BelongsToMany::class);

    $artist->asComposer()->attach($tales = [
        Tale::factory()->create(['year' => 1978])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'b'])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'a'])->id,
    ]);

    expect($artist->asComposer)->toHaveCount(3);

    expect($artist->asComposer->get(2)->id)->toBe($tales[0]);
    expect($artist->asComposer->get(1)->id)->toBe($tales[1]);
    expect($artist->asComposer->get(0)->id)->toBe($tales[2]);
});

it('can get its appearances as actor', function () {
    $artist = Artist::factory()->create();

    expect($artist->asActor())->toBeInstanceOf(BelongsToMany::class);

    $artist->asActor()->attach($tales = [
        Tale::factory()->create(['year' => 1978])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'b'])->id,
        Tale::factory()->create(['year' => 1969, 'title' => 'a'])->id,
    ]);

    expect($artist->asActor)->toHaveCount(3);

    expect($artist->asActor->get(2)->id)->toBe($tales[0]);
    expect($artist->asActor->get(1)->id)->toBe($tales[1]);
    expect($artist->asActor->get(0)->id)->toBe($tales[2]);
});

test('countAppearances scope works', function () {
    $artist = Artist::factory()->create();

    Tale::factory()->create(['director_id' => $artist->id]);
    Tale::factory()->create(['director_id' => $artist->id]);

    $artist->asLyricist()->attach(
        Tale::factory()->count(4)->create()->map->id,
    );

    $artist->asComposer()->attach(
        Tale::factory()->count(1)->create()->map->id,
    );

    $artist->asActor()->attach(
        Tale::factory()->count(6)->create()->map->id,
    );

    expect(Artist::countAppearances()->find($artist->id)->appearances)->toBe(13);
});

test('appearances method works', function () {
    $artist = Artist::factory()->create();

    Tale::factory()->create(['director_id' => $artist->id]);
    Tale::factory()->create(['director_id' => $artist->id]);

    $artist->asLyricist()->attach(
        Tale::factory()->count(4)->create()->map->id,
    );

    $artist->asComposer()->attach(
        Tale::factory()->count(1)->create()->map->id,
    );

    $artist->asActor()->attach(
        Tale::factory()->count(6)->create()->map->id,
    );

    expect($artist->appearances())->toBe(13);
});

it('can flush cached data', function () {
    $extract = "<p><b>Piotr Fronczewski</b> (ur. 8 czerwca 1946 w Łodzi) – polski aktor.\n</p>";

    $newExtract = "<p><b>Franek Kimono</b> (ur. 8 czerwca 1946 w Łodzi) – polski muzyk.\n</p>";

    $artist = Artist::factory()->create([
        'wikipedia' => 'Piotr_Fronczewski',
        'discogs' => 602473,
    ]);

    Http::fake([
        'pl.wikipedia.org/*' => Http::response([
            'query' => ['pages' => [3462 => ['extract' => $extract]]]
        ], 200),
    ]);

    expect($artist->wikipedia_extract)->toBe($extract);

    Http::clearResolvedInstances();

    Http::fake([
        'pl.wikipedia.org/*' => Http::response([
            'query' => ['pages' => [3462 => ['extract' => $newExtract]]]
        ], 200),
    ]);

    expect($artist->wikipedia_extract)->toBe($extract);

    Discogs::shouldReceive('forget')
        ->with(602473)
        ->andReturn(true);

    expect($artist->flushCache())->toBeTrue();

    expect($artist->wikipedia_extract)->toBe($newExtract);
});

test('findBySlug method works', function () {
    expect(Artist::findBySlug('Jan Matyjaszkiewicz'))->toBeNull();

    $artist = Artist::factory()
        ->create(['name' => 'Jan Matyjaszkiewicz']);

    expect(Artist::findBySlug('Jan Matyjaszkiewicz'))->toBeNull();

    expect(
        $artist->is(Artist::findBySlug('jan-matyjaszkiewicz'))
    )->toBeTrue();
});

test('findBySlugOrNew method works', function () {
    $artist = Artist::factory()
        ->create(['name' => 'Jan Matyjaszkiewicz']);

    expect(Artist::count())->toBe(1);

    expect(
        $artist->is(Artist::findBySlugOrNew(' jan matyjaSZkiewiCZ'))
    )->toBeTrue();

    expect(Artist::count())->toBe(1);

    $artist = Artist::findBySlugOrNew('Jan Kobuszewski');

    expect(Artist::count())->toBe(2);

    expect($artist->name)->toBe('Jan Kobuszewski')
        ->and($artist->slug)->toBe('jan-kobuszewski');
});
