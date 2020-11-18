<?php

use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
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
    $director = Artist::factory()->create();
    $lyricists = Artist::factory()->count(2)->create();
    $composers = Artist::factory()->count(2)->create();

    $credits = array_merge(
        [[
            'artist' => $director->name,
            'type' => CreditType::director()->value,
            'nr' => 0,
        ]],
        $lyricists->map(fn ($lyricist, $nr) => [
            'artist' => $lyricist->name,
            'type' => CreditType::lyricist()->value,
            'nr' => $nr,
        ])->all(),
        $composers->map(fn ($composer, $nr) => [
            'artist' => $composer->name,
            'type' => CreditType::composer()->value,
            'nr' => $nr,
        ])->all()
    );

    $actors = Artist::factory()->count(2)->create();

    $actorsCredits = $actors->map(fn ($composer, $credit_nr) => [
        'artist' => $composer->slug,
        'characters' => 'Zbójca ' . ($credit_nr + 1),
        'credit_nr' => $credit_nr + 1,
    ])->all();

    $attributes = array_merge(
        $this->attributes,
        [
            'credits' => $credits,
            'actors' => $actorsCredits,
        ]
    );

    asUser()
        ->post('bajki', $attributes)
        ->assertRedirect("bajki/o-dwoch-takich-co-ukradli-ksiezyc");

    $tale = Tale::first();

    foreach ($this->attributes as $key => $attribute) {
        expect($tale->$key)->toBe($attribute);
    }

    $directorCredits = $tale->creditsFor(CreditType::director());

    expect($directorCredits)->toHaveCount(1);
    expect($directorCredits->first()->id)->toBe($director->id);

    $lyricistsCredits = $tale->creditsFor(CreditType::lyricist());

    expect($lyricistsCredits)->toHaveCount(2);
    expect($lyricistsCredits[0]->id)->toBe($lyricists[0]->id)
        ->and($lyricistsCredits[0]->credit->nr)->toBe(0);
    expect($lyricistsCredits[1]->id)->toBe($lyricists[1]->id)
        ->and($lyricistsCredits[1]->credit->nr)->toBe(1);

    $composersCredits = $tale->creditsFor(CreditType::composer());

    expect($composersCredits)->toHaveCount(2);

    expect($composersCredits[0]->id)->toBe($composers[0]->id)
        ->and($composersCredits[0]->credit->nr)->toBe(0);
    expect($composersCredits[1]->id)->toBe($composers[1]->id)
        ->and($composersCredits[1]->credit->nr)->toBe(1);

    expect($tale->actors)->toHaveCount(2);

    expect($tale->actors[0]->id)->toBe($actors[0]->id)
        ->and($tale->actors[0]->pivot->characters)->toBe('Zbójca 1')
        ->and($tale->actors[0]->pivot->credit_nr)->toBe('1');

    expect($tale->actors[1]->id)->toBe($actors[1]->id)
        ->and($tale->actors[1]->pivot->characters)->toBe('Zbójca 2')
        ->and($tale->actors[1]->pivot->credit_nr)->toBe('2');
});
