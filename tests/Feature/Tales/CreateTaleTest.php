<?php

namespace Tests\Feature\Tales;

use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class CreateTaleTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->attributes = [
            'slug' => 'o-dwoch-takich-co-ukradli-ksiezyc',
            'title' => 'O dwóch takich co ukradli księżyc',
            'year' => 1976,
            'nr' => '28',
            'discogs' => 1211239,
        ];
    }

    #[TestDox('guests are asked to log in when attempting to view create tale form')]
    public function testGuestView(): void
    {
        $this->get('bajki/create')->assertRedirect('login');
    }

    #[TestDox('users can view create tale form')]
    public function testUserVuew(): void
    {
        $this->asUser()->get('bajki/create')->assertOk();
    }

    #[TestDox('guests cannot create tale')]
    public function testGuestCreate(): void
    {
        $this->post('bajki', $this->attributes)->assertRedirect('login');

        $this->assertSame(0, Tale::count());
    }

    #[TestDox('users with permissions can create tale')]
    public function testUserCreate(): void
    {
        $director = Artist::factory()->createOne();

        /** @var Collection<int, Artist> $lyricists */
        $lyricists = Artist::factory(2)->create();

        /** @var Collection<int, Artist> $composers */
        $composers = Artist::factory(2)->create();

        $credits = [
            [
                'artist' => $director->name,
                'type' => 'directing',
                'as' => 'Reżysor',
                'nr' => 0,
            ],
            ...$lyricists->map(fn ($lyricist, $nr) => [
                'artist' => $lyricist->name,
                'type' => 'text',
                'as' => null,
                'nr' => $nr,
            ]),
            ...$composers->map(fn ($composer, $nr) => [
                'artist' => $composer->name,
                'type' => 'music',
                'as' => null,
                'nr' => $nr,
            ]),
        ];

        /** @var Collection<int, Artist> $actors */
        $actors = Artist::factory(2)->create();

        $actorsCredits = $actors->map(fn ($composer, $credit_nr) => [
            'artist' => $composer->slug,
            'characters' => 'Zbójca ' . ($credit_nr + 1),
            'credit_nr' => $credit_nr + 1,
        ])->all();

        $attributes = [
            ...$this->attributes,
            'credits' => $credits,
            'actors' => $actorsCredits,
        ];

        $this->asUser()
            ->post('bajki', $attributes)
            ->assertRedirect('bajki/o-dwoch-takich-co-ukradli-ksiezyc');

        $tale = Tale::first();

        foreach ($this->attributes as $key => $attribute) {
            $this->assertSame($attribute, $tale->{$key});
        }

        $directorCredits = $tale->creditsFor(CreditType::Directing);

        $this->assertCount(1, $directorCredits);

        $this->assertSame($director->id, $directorCredits[0]->id);
        $this->assertSame('Reżysor', $directorCredits[0]->credit->as);
        $this->assertSame(0, $directorCredits[0]->credit->nr);

        $lyricistsCredits = $tale->creditsFor(CreditType::Text);

        $this->assertCount(2, $lyricistsCredits);

        $this->assertSame($lyricists[0]->id, $lyricistsCredits[0]->id);
        $this->assertNull($lyricistsCredits[0]->credit->as);
        $this->assertSame(0, $lyricistsCredits[0]->credit->nr);

        $this->assertSame($lyricists[1]->id, $lyricistsCredits[1]->id);
        $this->assertNull($lyricistsCredits[1]->credit->as);
        $this->assertSame(1, $lyricistsCredits[1]->credit->nr);

        $composersCredits = $tale->creditsFor(CreditType::Music);

        $this->assertCount(2, $composersCredits);

        $this->assertSame($composers[0]->id, $composersCredits[0]->id);
        $this->assertSame(0, $composersCredits[0]->credit->nr);

        $this->assertSame($composers[1]->id, $composersCredits[1]->id);
        $this->assertSame(1, $composersCredits[1]->credit->nr);

        $this->assertCount(2, $tale->actors);

        $this->assertSame($actors[0]->id, $tale->actors[0]->id);
        $this->assertSame('Zbójca 1', $tale->actors[0]->credit->characters);
        $this->assertSame(1, $tale->actors[0]->credit->credit_nr);

        $this->assertSame($actors[1]->id, $tale->actors[1]->id);
        $this->assertSame('Zbójca 2', $tale->actors[1]->credit->characters);
        $this->assertSame(2, $tale->actors[1]->credit->credit_nr);
    }
}
