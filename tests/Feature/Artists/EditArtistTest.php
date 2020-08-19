<?php

use App\Artist;

use function Pest\Laravel\{get, put};
use function Tests\asUser;

beforeEach(function () {
    $this->oldAttributes = [
        'slug' => 'ilona-kusmierska',
        'name' => 'Ilona Kuśmierska',
        'discogs' => 602488,
        'imdb' => '0476350',
        'wikipedia' => 'Ilona_Kuśmierska',
    ];

    $this->newAttributes = [
        'slug' => 'tadeusz-bartosik',
        'name' => 'Tadeusz Bartosik',
        'discogs' => 1023394,
        'imdb' => '0059302',
        'wikipedia' => 'Tadeusz_Bartosik',
    ];

    $this->artist = factory(Artist::class)->create($this->oldAttributes);
});

test('guests are asked to log in when attempting to view edit artist form', function () {
    get("artysci/{$this->artist->slug}/edit")
        ->assertRedirect('login');
});

test('guests are asked to log in when attempting to view edit form for nonexistent artist')
    ->get('artysci/2137/edit')
    ->assertRedirect('login');

test('users can view edit artist form', function () {
    asUser()
        ->get("artysci/{$this->artist->slug}/edit")
        ->assertOk();
});

test('guests cannot edit artist', function () {
    put("artysci/{$this->artist->slug}", $this->newAttributes)
        ->assertRedirect('login');

    $artist = $this->artist->fresh();

    foreach ($this->oldAttributes as $key => $attribute) {
        assertEquals($attribute, $artist->$key);
    }
});

test('users with permissions can edit artist', function () {
    asUser()
        ->put("artysci/{$this->artist->slug}", $this->newAttributes)
        ->assertRedirect("artysci/{$this->newAttributes['slug']}");

    $artist = $this->artist->fresh();

    foreach ($this->newAttributes as $key => $attribute) {
        assertEquals($attribute, $artist->$key);
    }
});
