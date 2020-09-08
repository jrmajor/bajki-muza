<?php

use App\Artist;
use App\Tale;

use function Pest\Laravel\{get, post};
use function Tests\asUser;

beforeEach(function () {
    $this->attributes = [
        'slug' => 'o-dwoch-takich-co-ukradli-ksiezyc',
        'title' => 'O dwóch takich co ukradli księżyc',
        'year' => 1976,
        'nr' => '28',
    ];
});

test('guests are asked to log in when attempting to view create tale form', function () {
    get('bajki/create')
        ->assertRedirect('login');
});

test('users can view create tale form', function () {
    asUser()
        ->get('bajki/create')
        ->assertOk();
});

test('guests cannot create tale', function () {
    post('bajki', $this->attributes)
        ->assertRedirect('login');

    expect(Tale::count())->toBe(0);
});

test('users with permissions can create tale', function () {
    $director = factory(Artist::class)->create();
    $lyricists = factory(Artist::class, 2)->create();
    $composers = factory(Artist::class, 2)->create();
    $actors = factory(Artist::class, 2)->create();

    $attributes = array_merge(
        $this->attributes,
        [
            'director' => $director->slug,
            'lyricists' => $lyricists->map(
                function ($lyricist, $credit_nr) {
                    return [
                        'artist' => $lyricist->slug,
                        'credit_nr' => $credit_nr + 1,
                    ];
                }
            )->all(),
            'composers' => $composers->map(
                function ($composer, $credit_nr) {
                    return [
                        'artist' => $composer->slug,
                        'credit_nr' => $credit_nr + 1,
                    ];
                }
            )->all(),
            'actors' => $actors->map(
                function ($composer, $credit_nr) {
                    return [
                        'artist' => $composer->slug,
                        'characters' => 'Zbójca ' . ($credit_nr + 1),
                        'credit_nr' => $credit_nr + 1,
                    ];
                }
            )->all(),
        ],
    );

    asUser()
        ->post('bajki', $attributes)
        ->assertRedirect("bajki/{$this->attributes['slug']}");

    $tale = Tale::first();

    foreach ($this->attributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }

    expect($director->id)->toBe($tale->director->id);

    expect($tale->lyricists)->toHaveCount(2);

    expect($tale->lyricists[0]->id)->toBe($lyricists[0]->id)
        ->and($tale->lyricists[0]->pivot->credit_nr)->toBe('1');

    expect($tale->lyricists[1]->id)->toBe($lyricists[1]->id)
        ->and($tale->lyricists[1]->pivot->credit_nr)->toBe('2');

    expect($tale->composers)->toHaveCount(2);

    expect($tale->composers[0]->id)->toBe($composers[0]->id)
        ->and($tale->composers[0]->pivot->credit_nr)->toBe('1');

    expect($tale->composers[1]->id)->toBe($composers[1]->id)
        ->and($tale->composers[1]->pivot->credit_nr)->toBe('2');

    expect($tale->actors)->toHaveCount(2);

    expect($tale->actors[0]->id)->toBe($actors[0]->id)
        ->and($tale->actors[0]->pivot->characters)->toBe('Zbójca 1')
        ->and($tale->actors[0]->pivot->credit_nr)->toBe('1');

    expect($tale->actors[1]->id)->toBe($actors[1]->id)
        ->and($tale->actors[1]->pivot->characters)->toBe('Zbójca 2')
        ->and($tale->actors[1]->pivot->credit_nr)->toBe('2');
});
