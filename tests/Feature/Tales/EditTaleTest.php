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
        'cover' => '3019cfd0454e8c057e6af28b7b19e074',
    ];

    $this->newAttributes = [
        'slug' => 'o-dwoch-takich-co-ukradli-ksiezyc',
        'title' => 'O dwóch takich co ukradli księżyc',
        'year' => 1976,
        'nr' => '28',
        'cover' => '7681e9ebb8bc22bfaa2dad3f947ddb8c',
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
        assertEquals($attribute, $tale->$key);
    }
});

test('users with permissions can edit tale attributes', function () {
    asUser()
        ->put("bajki/{$this->tale->slug}", $this->newAttributes)
        ->assertRedirect("bajki/{$this->newAttributes['slug']}");

    $tale = $this->tale->fresh();

    foreach ($this->newAttributes as $key => $attribute) {
        assertEquals($attribute, $tale->$key);
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

    assertEquals($tale->director->id, $director->id);
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

    assertCount(2, $tale->lyricists);

    assertEquals($lyricists[0]->id, $tale->lyricists[0]->id);
    assertEquals(1, $tale->lyricists[0]->pivot->credit_nr);

    assertEquals($lyricists[1]->id, $tale->lyricists[1]->id);
    assertEquals(2, $tale->lyricists[1]->pivot->credit_nr);
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

    assertCount(2, $tale->composers);

    assertEquals($composers[0]->id, $tale->composers[0]->id);
    assertEquals(1, $tale->composers[0]->pivot->credit_nr);

    assertEquals($composers[1]->id, $tale->composers[1]->id);
    assertEquals(2, $tale->composers[1]->pivot->credit_nr);
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

    assertCount(2, $tale->actors);

    assertEquals($actors[0]->id, $tale->actors[0]->id);
    assertEquals('Zbójca 1', $tale->actors[0]->pivot->characters);
    assertEquals(1, $tale->actors[0]->pivot->credit_nr);

    assertEquals($actors[1]->id, $tale->actors[1]->id);
    assertEquals('Zbójca 2', $tale->actors[1]->pivot->characters);
    assertEquals(2, $tale->actors[1]->pivot->credit_nr);
});

test('users with permissions can remove tale relations', function () {
    asUser()
        ->put("bajki/{$this->tale->slug}", $this->oldAttributes)
        ->assertRedirect("bajki/{$this->tale->slug}");

    $tale = $this->tale->fresh();

    assertNull($tale->director);
    assertCount(0, $tale->lyricists);
    assertCount(0, $tale->composers);
    assertCount(0, $tale->actors);
});
