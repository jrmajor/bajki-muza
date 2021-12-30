<?php

namespace Tests\Feature\Tales;

use App\Models\Artist;
use App\Models\Tale;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class EditTaleTest extends TestCase
{
    private array $oldAttributes;

    private array $newAttributes;

    private Tale $tale;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->tale = Tale::factory()->createOne($this->oldAttributes);
    }

    #[TestDox('guests are asked to log in when attempting to view edit tale form')]
    public function testGuestView(): void
    {
        $this->get('bajki/drzewko-aby-baby/edit')->assertRedirect('login');
    }

    #[TestDox('guests are asked to log in when attempting to view edit form for nonexistent tale')]
    public function testGuestNonexistent(): void
    {
        $this->get('bajki/2137/edit')->assertRedirect('login');
    }

    #[TestDox('users can view edit tale form')]
    public function testUserView(): void
    {
        $this->asUser()->get('bajki/drzewko-aby-baby/edit')->assertOk();
    }

    #[TestDox('guests cannot edit tale attributes')]
    public function testGuestEdit(): void
    {
        $this->put('bajki/drzewko-aby-baby', $this->newAttributes)
            ->assertRedirect('login');

        $this->tale->refresh();

        foreach ($this->oldAttributes as $key => $attribute) {
            $this->assertSame($attribute, $this->tale->{$key});
        }
    }

    #[TestDox('users with permissions can edit tale attributes')]
    public function testUserEdit(): void
    {
        $this->asUser()
            ->put('bajki/drzewko-aby-baby', $this->newAttributes)
            ->assertRedirect('bajki/o-dwoch-takich-co-ukradli-ksiezyc');

        $this->tale->refresh();

        foreach ($this->newAttributes as $key => $attribute) {
            $this->assertSame($attribute, $this->tale->{$key});
        }
    }

    #[TestDox('users with permissions can add credits')]
    public function testUserAddCredits(): void
    {
        $director = Artist::factory()->createOne();

        $lyricistAndComposer = Artist::factory()->createOne();

        /** @var Collection<Artist> $lyricists */
        $lyricists = Artist::factory(2)->create();
        $lyricists->push($lyricistAndComposer);

        /** @var Collection<Artist> $composers */
        $composers = Artist::factory(2)->create();
        $composers->push($lyricistAndComposer);

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

        $attributes = [...$this->oldAttributes, 'credits' => $credits];

        $this->asUser()
            ->put('bajki/drzewko-aby-baby', $attributes)
            ->assertRedirect('bajki/drzewko-aby-baby');

        $this->tale->refresh();

        $directorCredits = $this->tale->creditsFor(CreditType::Directing);

        $this->assertCount(1, $directorCredits);
        $this->assertSame($director->id, $directorCredits[0]->id);
        $this->assertSame('Reżysor', $directorCredits[0]->credit->as);
        $this->assertSame(0, $directorCredits[0]->credit->nr);

        $lyricistsCredits = $this->tale->creditsFor(CreditType::Text);

        $this->assertCount(3, $lyricistsCredits);
        $this->assertSame($lyricists[0]->id, $lyricistsCredits[0]->id);
        $this->assertNull($lyricistsCredits[0]->credit->as);
        $this->assertSame(0, $lyricistsCredits[0]->credit->nr);
        $this->assertSame($lyricists[1]->id, $lyricistsCredits[1]->id);
        $this->assertNull($lyricistsCredits[1]->credit->as);
        $this->assertSame(1, $lyricistsCredits[1]->credit->nr);
        $this->assertSame($lyricistAndComposer->id, $lyricistsCredits[2]->id);
        $this->assertNull($lyricistsCredits[2]->credit->as);
        $this->assertSame(2, $lyricistsCredits[2]->credit->nr);

        $composersCredits = $this->tale->creditsFor(CreditType::Music);

        $this->assertCount(3, $composersCredits);
        $this->assertSame(0, $composersCredits[0]->credit->nr);
        $this->assertSame($composers[0]->id, $composersCredits[0]->id);
        $this->assertSame(1, $composersCredits[1]->credit->nr);
        $this->assertSame($composers[1]->id, $composersCredits[1]->id);
        $this->assertSame(2, $composersCredits[2]->credit->nr);
        $this->assertSame($lyricistAndComposer->id, $composersCredits[2]->id);
    }

    #[TestDox('users with permissions can add tale actors')]
    public function testUserAddActors(): void
    {
        /** @var Collection<Artist> $actors */
        $actors = Artist::factory(2)->create();

        $actorsCredits = $actors->map(fn (Artist $composer, int $creditNr) => [
            'artist' => $composer->slug,
            'characters' => 'Zbójca ' . ($creditNr + 1),
            'credit_nr' => $creditNr + 1,
        ])->all();

        $attributes = [...$this->oldAttributes, 'actors' => $actorsCredits];

        $this->asUser()
            ->put('bajki/drzewko-aby-baby', $attributes)
            ->assertRedirect('bajki/drzewko-aby-baby');

        $tale = $this->tale->fresh();

        $this->assertCount(2, $tale->actors);

        $this->assertSame($actors[0]->id, $tale->actors[0]->id);
        $this->assertSame('Zbójca 1', $tale->actors[0]->credit->characters);
        $this->assertSame(1, $tale->actors[0]->credit->credit_nr);

        $this->assertSame($actors[1]->id, $tale->actors[1]->id);
        $this->assertSame('Zbójca 2', $tale->actors[1]->credit->characters);
        $this->assertSame(2, $tale->actors[1]->credit->credit_nr);
    }

    #[TestDox('users with permissions can remove tale relations')]
    public function testUserRemoveRelations(): void
    {
        $this->asUser()
            ->put('bajki/drzewko-aby-baby', $this->oldAttributes)
            ->assertRedirect('bajki/drzewko-aby-baby');

        $tale = $this->tale->fresh();

        $this->assertEmpty($tale->credits);
        $this->assertEmpty($tale->actors);
    }
}
