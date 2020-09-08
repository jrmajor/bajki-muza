<?php

use App\Artist;
use App\Tale;

use function Pest\Laravel\{get, put};
use function Tests\asUser;

beforeEach(function () {
    $this->oldAttributes = [
        'slug' => 'drzewko-aby-baby',
        'title' => 'Drzewko Aby Baby',
        'year' => 1986,
        'nr' => '58',
    ];

    $this->newAttributes = [
        'slug' => 'o-dwoch-takich-co-ukradli-ksiezyc',
        'title' => 'O dwóch takich co ukradli księżyc',
        'year' => 1976,
        'nr' => '28',
    ];

    $this->tale = factory(Tale::class)->create($this->oldAttributes);
});

test('guests are asked to log in when attempting to view edit tale form', function () {
    get("bajki/{$this->tale->slug}/edit")
        ->assertRedirect('login');
});

test('guests are asked to log in when attempting to view edit form for nonexistent tale')
    ->get('bajki/2137/edit')
    ->assertRedirect('login');

test('users can view edit tale form', function () {
    asUser()
        ->get("bajki/{$this->tale->slug}/edit")
        ->assertOk();
});

test('guests cannot edit tale attributes', function () {
    put("bajki/{$this->tale->slug}", $this->newAttributes)
        ->assertRedirect('login');

    $tale = $this->tale->fresh();

    foreach ($this->oldAttributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }
});

test('users with permissions can edit tale attributes', function () {
    asUser()
        ->put("bajki/{$this->tale->slug}", $this->newAttributes)
        ->assertRedirect("bajki/{$this->newAttributes['slug']}");

    $tale = $this->tale->fresh();

    foreach ($this->newAttributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }
});

test('users with permissions can add tale director', function () {
    $director = factory(Artist::class)->create();

    asUser()
        ->put(
            "bajki/{$this->tale->slug}",
            array_merge(
                $this->oldAttributes,
                ['director' => $director->slug]
            )
        )->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    expect($director->id)->toBe($tale->director->id);
});

test('users with permissions can add tale lyricists', function () {
    $lyricists = factory(Artist::class, 2)->create();

    asUser()
        ->put(
            "bajki/{$this->tale->slug}",
            array_merge(
                $this->oldAttributes,
                [
                    'lyricists' => $lyricists->map(
                        function ($lyricist, $credit_nr) {
                            return [
                                'artist' => $lyricist->slug,
                                'credit_nr' => $credit_nr + 1,
                            ];
                        }
                    )->all()
                ]
            )
        )->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    expect($tale->lyricists)->toHaveCount(2);

    expect($tale->lyricists[0]->id)->toBe($lyricists[0]->id);
    expect($tale->lyricists[0]->pivot->credit_nr)->toBe('1');

    expect($tale->lyricists[1]->id)->toBe($lyricists[1]->id);
    expect($tale->lyricists[1]->pivot->credit_nr)->toBe('2');
});

test('users with permissions can add tale composers', function () {
    $composers = factory(Artist::class, 2)->create();

    asUser()
        ->put(
            "bajki/{$this->tale->slug}",
            array_merge(
                $this->oldAttributes,
                [
                    'composers' => $composers->map(
                        function ($composer, $credit_nr) {
                            return [
                                'artist' => $composer->slug,
                                'credit_nr' => $credit_nr + 1,
                            ];
                        }
                    )->all()
                ]
            )
        )->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    expect($tale->composers)->toHaveCount(2);

    expect($tale->composers[0]->id)->toBe($composers[0]->id);
    expect($tale->composers[0]->pivot->credit_nr)->toBe('1');

    expect($tale->composers[1]->id)->toBe($composers[1]->id);
    expect($tale->composers[1]->pivot->credit_nr)->toBe('2');
});

test('users with permissions can add tale actors', function () {
    $actors = factory(Artist::class, 2)->create();

    asUser()
        ->put(
            "bajki/{$this->tale->slug}",
            array_merge(
                $this->oldAttributes,
                [
                    'actors' => $actors->map(
                        function ($composer, $credit_nr) {
                            return [
                                'artist' => $composer->slug,
                                'characters' => 'Zbójca ' . ($credit_nr + 1),
                                'credit_nr' => $credit_nr + 1,
                            ];
                        }
                    )->all()
                ]
            )
        )->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    expect($tale->actors)->toHaveCount(2);

    expect($tale->actors[0]->id)->toBe($actors[0]->id);
    expect($tale->actors[0]->pivot->characters)->toBe('Zbójca 1');
    expect($tale->actors[0]->pivot->credit_nr)->toBe('1');

    expect($tale->actors[1]->id)->toBe($actors[1]->id);
    expect($tale->actors[1]->pivot->characters)->toBe('Zbójca 2');
    expect($tale->actors[1]->pivot->credit_nr)->toBe('2');
});

test('users with permissions can remove tale relations', function () {
    asUser()
        ->put("bajki/{$this->tale->slug}", $this->oldAttributes)
        ->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    expect($tale->director)->toBeNull();
    expect($tale->lyricists)->toHaveCount(0);
    expect($tale->composers)->toHaveCount(0);
    expect($tale->actors)->toHaveCount(0);
});
