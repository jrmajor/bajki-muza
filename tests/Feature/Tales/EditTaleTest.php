<?php

use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Tests\asUser;

beforeEach(function () {
    $this->oldAttributes = [
        'slug' => 'drzewko-aby-baby',
        'title' => 'Drzewko Aby Baby',
        'year' => 1986,
        'nr' => '58',
        'discogs' => 3962982,
    ];

    $this->newAttributes = [
        'slug' => 'o-dwoch-takich-co-ukradli-ksiezyc',
        'title' => 'O dwóch takich co ukradli księżyc',
        'year' => 1976,
        'nr' => '28',
        'discogs' => 1211239,
    ];

    $this->tale = Tale::factory()->create($this->oldAttributes);
});

test('guests are asked to log in when attempting to view edit tale form', function () {
    get('bajki/drzewko-aby-baby/edit')
        ->assertRedirect('login');
});

test('guests are asked to log in when attempting to view edit form for nonexistent tale')
    ->get('bajki/2137/edit')
    ->assertRedirect('login');

test('users can view edit tale form')
    ->asUser()
    ->get('bajki/drzewko-aby-baby/edit')
    ->assertOk();

test('guests cannot edit tale attributes', function () {
    put('bajki/drzewko-aby-baby', $this->newAttributes)
        ->assertRedirect('login');

    $tale = $this->tale->fresh();

    foreach ($this->oldAttributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }
});

test('users with permissions can edit tale attributes', function () {
    asUser()
        ->put('bajki/drzewko-aby-baby', $this->newAttributes)
        ->assertRedirect('bajki/o-dwoch-takich-co-ukradli-ksiezyc');

    $tale = $this->tale->fresh();

    foreach ($this->newAttributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }
});

test('users with permissions can add credits', function () {
    $director = Artist::factory()->create();

    $lyricistAndComposer = Artist::factory()->create();

    $lyricists = Artist::factory()->count(2)->create()
        ->push($lyricistAndComposer);

    $composers = Artist::factory()->count(2)->create()
        ->push($lyricistAndComposer);

    $credits = array_merge(
        [[
            'artist' => $director->name,
            'type' => CreditType::directing()->value,
            'as' => 'Reżysor',
            'nr' => 0,
        ]],
        $lyricists->map(fn ($lyricist, $nr) => [
            'artist' => $lyricist->name,
            'type' => CreditType::text()->value,
            'as' => null,
            'nr' => $nr,
        ])->all(),
        $composers->map(fn ($composer, $nr) => [
            'artist' => $composer->name,
            'type' => CreditType::music()->value,
            'as' => null,
            'nr' => $nr,
        ])->all(),
    );

    $attributes = array_merge(
        $this->oldAttributes,
        ['credits' => $credits],
    );

    asUser()
        ->put('bajki/drzewko-aby-baby', $attributes)
        ->assertRedirect('bajki/drzewko-aby-baby');

    $this->tale->refresh();

    $directorCredits = $this->tale->creditsFor(CreditType::directing());

    expect($directorCredits)->toHaveCount(1);
    expect($directorCredits[0]->id)->toBe($director->id)
        ->and($directorCredits[0]->credit->as)->toBe('Reżysor')
        ->and($directorCredits[0]->credit->nr)->toBe(0);

    $lyricistsCredits = $this->tale->creditsFor(CreditType::text());

    expect($lyricistsCredits)->toHaveCount(3);
    expect($lyricistsCredits[0]->id)->toBe($lyricists[0]->id)
        ->and($lyricistsCredits[0]->credit->as)->toBeNull()
        ->and($lyricistsCredits[0]->credit->nr)->toBe(0);
    expect($lyricistsCredits[1]->id)->toBe($lyricists[1]->id)
        ->and($lyricistsCredits[1]->credit->as)->toBeNull()
        ->and($lyricistsCredits[1]->credit->nr)->toBe(1);
    expect($lyricistsCredits[2]->id)->toBe($lyricistAndComposer->id)
        ->and($lyricistsCredits[2]->credit->as)->toBeNull()
        ->and($lyricistsCredits[2]->credit->nr)->toBe(2);

    $composersCredits = $this->tale->creditsFor(CreditType::music());

    expect($composersCredits)->toHaveCount(3);

    expect($composersCredits[0]->id)->toBe($composers[0]->id)
        ->and($composersCredits[0]->credit->nr)->toBe(0);
    expect($composersCredits[1]->id)->toBe($composers[1]->id)
        ->and($composersCredits[1]->credit->nr)->toBe(1);
    expect($composersCredits[2]->id)->toBe($lyricistAndComposer->id)
        ->and($composersCredits[2]->credit->nr)->toBe(2);
});

test('users with permissions can add tale actors', function () {
    $actors = Artist::factory()->count(2)->create();

    $actorsCredits = $actors->map(fn ($composer, $credit_nr) => [
        'artist' => $composer->slug,
        'characters' => 'Zbójca '.($credit_nr + 1),
        'credit_nr' => $credit_nr + 1,
    ])->all();

    $attributes = array_merge(
        $this->oldAttributes,
        ['actors' => $actorsCredits],
    );

    asUser()
        ->put('bajki/drzewko-aby-baby', $attributes)
        ->assertRedirect('bajki/drzewko-aby-baby');

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
        ->put('bajki/drzewko-aby-baby', $this->oldAttributes)
        ->assertRedirect('bajki/drzewko-aby-baby');

    $tale = $this->tale->fresh();

    expect($tale->credits)->toBeEmpty();
    expect($tale->actors)->toBeEmpty();
});
