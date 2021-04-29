<?php

use App\Images\Cover;
use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditData;
use App\Values\CreditType;
use Facades\App\Services\Discogs;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('casts year to integer')
    ->expect((new Tale(['year' => '1973']))->year)->toBe(1973);

it('casts discogs id to integer')
    ->expect((new Tale(['discogs' => '2792351']))->discogs)->toBe(2792351);

it('generates slug when created', function () {
    $tale = Tale::create(['title' => 'O Tadku-Niejadku, babci i dziadku']);

    expect($tale->slug)->toBe('o-tadku-niejadku-babci-i-dziadku');

    $tale = new Tale();
    $tale->title = 'Ali Baba i czterdziestu rozbójników';

    expect($tale->slug)->toBeNull();

    $tale->save();

    expect($tale->slug)->toBe('ali-baba-i-czterdziestu-rozbojnikow');
});

it('regenerates slug when updated', function () {
    $tale = Tale::create(['title' => 'O Tadku-Niejadku, babci i dziadku']);

    expect($tale->slug)->toBe('o-tadku-niejadku-babci-i-dziadku');

    $tale->title = 'Ali Baba i czterdziestu rozbójników';

    $tale->save();

    expect($tale->slug)->toBe('ali-baba-i-czterdziestu-rozbojnikow');

    $tale->fill(['title' => 'Jak Janek i Marianek szczęścia szukali'])->save();

    expect($tale->slug)->toBe('jak-janek-i-marianek-szczescia-szukali');
});

it('can generate discogs url', function () {
    $tale = Tale::factory()
        ->make(['discogs' => 2792351]);

    Discogs::shouldReceive('releaseUrl')
        ->andReturn('https://www.discogs.com/release/2792351');

    expect($tale->discogs_url)
        ->toBe('https://www.discogs.com/release/2792351');
});

it('can get its cover', function () {
    $cover = Cover::create([
        'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
    ]);

    $tale = Tale::factory()->cover($cover)->create();

    expect($tale->cover)->toBeInstanceOf(Cover::class)
        ->and($tale->cover->filename())
        ->toBe('tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png');
});

it('can get its actors', function () {
    $tale = Tale::factory()->create();

    expect($tale->actors())->toBeInstanceOf(BelongsToMany::class);

    $tale->actors()->detach();

    $tale->actors()->attach($artists = [
        Artist::factory()->create()->id => [
            'characters' => 'Wróżka Bzów',
            'credit_nr' => 3,
        ],
        Artist::factory()->create()->id => [
            'characters' => 'Ośla Skórka',
            'credit_nr' => 1,
        ],
        Artist::factory()->create()->id => [
            'characters' => null,
            'credit_nr' => 2,
        ],
    ]);

    expect($tale->actors)->toHaveCount(3);

    $artists = array_keys($artists);

    expect($tale->actors->get(2)->id)->toBe($artists[0]);
    expect($tale->actors->get(0)->id)->toBe($artists[1]);
    expect($tale->actors->get(1)->id)->toBe($artists[2]);
});

it('can get credits', function () {
    $tale = Tale::factory()->withoutRelations()->create();

    expect($tale->credits())->toBeInstanceOf(BelongsToMany::class);

    $tale->credits()->attach($artists = [
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 2,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 1,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 0,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 1,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 0,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 2,
        ],
    ]);

    $credits = $tale->credits;

    expect($credits)->toHaveCount(6);

    $ids = array_keys($artists);

    expect($credits->get(0)->id)->toBe($ids[2]);
    expect($credits->get(1)->id)->toBe($ids[4]);
    expect($credits->get(2)->id)->toBe($ids[1]);
    expect($credits->get(3)->id)->toBe($ids[3]);
    expect($credits->get(4)->id)->toBe($ids[0]);
    expect($credits->get(5)->id)->toBe($ids[5]);
});

it('can get credits of given type', function () {
    $tale = Tale::factory()->withoutRelations()->create();

    $tale->credits()->attach($artists = [
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 2,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 1,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 1,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 0,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::text(),
            'nr' => 0,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::music(),
            'nr' => 2,
        ],
        Artist::factory()->create()->id => [
            'type' => CreditType::directing(),
            'nr' => 0,
        ],
    ]);

    $composers = $tale->creditsFor(CreditType::music());

    expect($composers)
        ->toBeInstanceOf(EloquentCollection::class)
        ->toHaveCount(3);

    $ids = array_keys($artists);

    expect($composers->get(0)->id)->toBe($ids[3]);
    expect($composers->get(1)->id)->toBe($ids[2]);
    expect($composers->get(2)->id)->toBe($ids[5]);
});

it('can sync credits', function () {
    $creditKeys = fn (Artist $artist) => Arr::only(
        $artist->credit->getAttributes(),
        ['type', 'as', 'nr'],
    );

    [$firstArtist, $secondArtist] = Artist::factory(2)->create();

    $tale = Tale::factory()->withoutRelations()->create();

    // no credits -> one credit

    $tale->syncCredits([
        $firstArtist->id => [
            new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
        ],
    ]);

    $tale->refresh();

    expect($tale->credits)->toHaveCount(1);

    expect($tale->credits[0])->toBeModel($firstArtist)
        ->and($creditKeys($tale->credits[0]))->toBe([
            'type' => 'directing', 'as' => 'Reżyserya', 'nr' => '0',
        ]);

    // one credit -> two credits for the same artist

    $tale->syncCredits([
        $firstArtist->id => [
            new CreditData(type: 'adaptation', as: null, nr: 1),
            new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
        ],
    ]);

    $tale->refresh();

    expect($tale->credits)->toHaveCount(2);

    expect($tale->credits[0])->toBeModel($firstArtist)
        ->and($creditKeys($tale->credits[0]))->toBe([
            'type' => 'directing', 'as' => 'Reżyserya', 'nr' => '0',
        ]);

    expect($tale->credits[1])->toBeModel($firstArtist)
        ->and($creditKeys($tale->credits[1]))->toBe([
            'type' => 'adaptation', 'as' => null, 'nr' => '1',
        ]);

    // two credits for the same artist -> two credits for two artists

    $tale->syncCredits([
        $firstArtist->id => [
            new CreditData(type: 'adaptation', as: null, nr: 1),
        ],
        $secondArtist->id => [
            new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
        ],
    ]);

    $tale->refresh();

    expect($tale->credits)->toHaveCount(2);

    expect($tale->credits[0])->toBeModel($secondArtist)
        ->and($creditKeys($tale->credits[0]))->toBe([
            'type' => 'directing', 'as' => 'Reżyserya', 'nr' => '0',
        ]);

    expect($tale->credits[1])->toBeModel($firstArtist)
        ->and($creditKeys($tale->credits[1]))->toBe([
            'type' => 'adaptation', 'as' => null, 'nr' => '1',
        ]);

    // two credits for the same artist -> change only credit attributes

    $tale->syncCredits([
        $firstArtist->id => [
            new CreditData(type: 'author', as: 'A-utor', nr: 2),
        ],
        $secondArtist->id => [
            new CreditData(type: 'music', as: null, nr: 3),
        ],
    ]);

    $tale->refresh();

    expect($tale->credits)->toHaveCount(2);

    expect($tale->credits[0])->toBeModel($firstArtist)
        ->and($creditKeys($tale->credits[0]))->toBe([
            'type' => 'author', 'as' => 'A-utor', 'nr' => '2',
        ]);

    expect($tale->credits[1])->toBeModel($secondArtist)
        ->and($creditKeys($tale->credits[1]))->toBe([
            'type' => 'music', 'as' => null, 'nr' => '3',
        ]);
});

test('withActorsPopularity scope works', function () {
    $artists = Artist::factory(3)->create();

    $artists[0]->asActor()->attach(
        $ids = Tale::factory(6)->create()->map->id,
    );

    $artists[1]->asActor()->attach(
        Tale::factory(3)->create()->map->id,
    );

    $artists[1]->asActor()->attach($ids[0]);

    $tale = Tale::factory()->withoutRelations()->create();

    $tale->actors()->attach($artists->map->id);

    expect(Tale::withActorsPopularity()->find($tale->id)->popularity)->toBe(13);
});
