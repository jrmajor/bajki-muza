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
        'cover' => '7681e9ebb8bc22bfaa2dad3f947ddb8c',
    ];
});

test('guests are asked to log in when attempting to view create tale form', function () {
    get("bajki/create")
        ->assertRedirect('login');
});

test('users can view create tale form', function () {
    asUser()
        ->get("bajki/create")
        ->assertOk();
});

test('guests cannot create tale', function () {
    post("bajki", $this->attributes)
        ->assertRedirect('login');

    assertEquals(0, Tale::count());
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
        ->post("bajki", $attributes)
        ->assertRedirect("bajki/{$this->attributes['slug']}");

    $tale = Tale::first();

    foreach ($this->attributes as $key => $attribute) {
        assertEquals($attribute, $tale->$key);
    }

    assertEquals($tale->director->id, $director->id);

    assertCount(2, $tale->lyricists);

    assertEquals($lyricists[0]->id, $tale->lyricists[0]->id);
    assertEquals(1, $tale->lyricists[0]->pivot->credit_nr);

    assertEquals($lyricists[1]->id, $tale->lyricists[1]->id);
    assertEquals(2, $tale->lyricists[1]->pivot->credit_nr);

    assertCount(2, $tale->composers);

    assertEquals($composers[0]->id, $tale->composers[0]->id);
    assertEquals(1, $tale->composers[0]->pivot->credit_nr);

    assertEquals($composers[1]->id, $tale->composers[1]->id);
    assertEquals(2, $tale->composers[1]->pivot->credit_nr);

    assertCount(2, $tale->actors);

    assertEquals($actors[0]->id, $tale->actors[0]->id);
    assertEquals('Zbójca 1', $tale->actors[0]->pivot->characters);
    assertEquals(1, $tale->actors[0]->pivot->credit_nr);

    assertEquals($actors[1]->id, $tale->actors[1]->id);
    assertEquals('Zbójca 2', $tale->actors[1]->pivot->characters);
    assertEquals(2, $tale->actors[1]->pivot->credit_nr);
});
