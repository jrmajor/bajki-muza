<?php

use App\Models\Artist;
use App\Models\Tale;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('casts discogs and filmpolski ids to integers', function () {
    $tale = Tale::factory()->create([
        'year' => '1973',
    ]);

    expect($tale->year)->toBe(1973)
        ->and($tale->year)->not->toBe('1973');
});

it('generates slug when created', function () {
    $tale = Tale::create(['title' => 'O Tadku-Niejadku, babci i dziadku']);

    expect($tale->slug)->toBe('o-tadku-niejadku-babci-i-dziadku');

    $tale = new Tale;
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

it('can get its cover', function () {
    Storage::fake('testing');

    $tale = Tale::factory()
        ->create(['cover' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png']);

    expect($tale->cover())->toBe('tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png');

    expect($tale->cover('256'))
        ->toContain('/covers/256/tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png');

    $tale->cover = null;

    expect($tale->cover('original'))->toBeNull();
});

it('can get its director', function () {
    $tale = Tale::factory()
        ->create(['director_id' => null]);

    expect($tale->director())->toBeInstanceOf(BelongsTo::class);

    expect($tale->director)->toBeNull();

    $artist = Artist::factory()->create();

    $tale->director_id = $artist->id;

    $tale->save();

    $tale = $tale->fresh();

    expect($tale->director)->toBeInstanceOf(Artist::class)
        ->and($artist->is($tale->director))->toBeTrue();
});

it('can get its lyricist', function () {
    $tale = Tale::factory()->create();

    expect($tale->lyricists())->toBeInstanceOf(BelongsToMany::class);

    $tale->lyricists()->detach();

    $tale->lyricists()->attach($artists = [
        Artist::factory()->create()->id => ['credit_nr' => 3],
        Artist::factory()->create()->id => ['credit_nr' => 1],
        Artist::factory()->create()->id => ['credit_nr' => 2],
    ]);

    expect($tale->lyricists)->toHaveCount(3);

    $artists = array_keys($artists);

    expect($tale->lyricists->get(2)->id)->toBe($artists[0]);
    expect($tale->lyricists->get(0)->id)->toBe($artists[1]);
    expect($tale->lyricists->get(1)->id)->toBe($artists[2]);
});

it('can get its composer', function () {
    $tale = Tale::factory()->create();

    expect($tale->composers())->toBeInstanceOf(BelongsToMany::class);

    $tale->composers()->detach();

    $tale->composers()->attach($artists = [
        Artist::factory()->create()->id => ['credit_nr' => 3],
        Artist::factory()->create()->id => ['credit_nr' => 1],
        Artist::factory()->create()->id => ['credit_nr' => 2],
    ]);

    expect($tale->composers)->toHaveCount(3);

    $artists = array_keys($artists);

    expect($tale->composers->get(2)->id)->toBe($artists[0]);
    expect($tale->composers->get(0)->id)->toBe($artists[1]);
    expect($tale->composers->get(1)->id)->toBe($artists[2]);
});

it('can get its actor', function () {
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
