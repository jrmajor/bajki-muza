<?php

namespace Tests\Unit\Models;

use App\Images\Cover;
use App\Models\Artist;
use App\Models\Tale;
use App\Services\Discogs;
use App\Values\CreditData;
use App\Values\CreditType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class TaleTest extends TestCase
{
    #[TestDox('it casts year to integer')]
    public function testCastYear(): void
    {
        $this->assertSame(1973, (new Tale(['year' => '1973']))->year);
    }

    #[TestDox('it casts discogs id to integer')]
    public function testCastDiscogs(): void
    {
        $this->assertSame(2792351, (new Tale(['discogs' => '2792351']))->discogs);
    }

    #[TestDox('it generates slug when created')]
    public function testSlugCreate(): void
    {
        $tale = Tale::create(['title' => 'O Tadku-Niejadku, babci i dziadku']);

        $this->assertSame('o-tadku-niejadku-babci-i-dziadku', $tale->slug);

        $tale = new Tale();
        $tale->title = 'Ali Baba i czterdziestu rozbójników';

        $this->assertNull($tale->slug);

        $tale->save();

        $this->assertSame('ali-baba-i-czterdziestu-rozbojnikow', $tale->slug);
    }

    #[TestDox('it regenerates slug when updated')]
    public function testSlugUpdate(): void
    {
        $tale = Tale::create(['title' => 'O Tadku-Niejadku, babci i dziadku']);

        $this->assertSame('o-tadku-niejadku-babci-i-dziadku', $tale->slug);

        $tale->title = 'Ali Baba i czterdziestu rozbójników';

        $tale->save();

        $this->assertSame('ali-baba-i-czterdziestu-rozbojnikow', $tale->slug);

        $tale->fill(['title' => 'Jak Janek i Marianek szczęścia szukali'])->save();

        $this->assertSame('jak-janek-i-marianek-szczescia-szukali', $tale->slug);
    }

    #[TestDox('it can get its cover')]
    public function testCover(): void
    {
        $cover = Cover::create([
            'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
        ]);

        $tale = Tale::factory()->cover($cover)->createOne();

        $this->assertInstanceOf(Cover::class, $tale->cover);
        $this->assertSame(
            'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
            $tale->cover->filename(),
        );
    }

    #[TestDox('it can generate discogs url')]
    public function testDiscogsUrl(): void
    {
        $tale = Tale::factory()->makeOne(['discogs' => 2792351]);

        $this->mock(Discogs::class)
            ->shouldReceive('releaseUrl')
            ->andReturn('https://www.discogs.com/release/2792351');

        $this->assertSame('https://www.discogs.com/release/2792351', $tale->discogs_url);
    }

    #[TestDox('it can get its actors')]
    public function testActors(): void
    {
        $tale = Tale::factory()->createOne();

        $tale->actors()->detach();

        $artists = Artist::factory(3)->create();

        $tale->actors()->attach([
            $artists[0]->id => [
                'characters' => 'Wróżka Bzów',
                'credit_nr' => 3,
            ],
            $artists[1]->id => [
                'characters' => 'Ośla Skórka',
                'credit_nr' => 1,
            ],
            $artists[2]->id => [
                'characters' => null,
                'credit_nr' => 2,
            ],
        ]);

        $actors = $tale->fresh()->actors;
        $this->assertCount(3, $actors);
        $this->assertSameModel($artists[1], $actors[0]);
        $this->assertSameModel($artists[2], $actors[1]);
        $this->assertSameModel($artists[0], $actors[2]);
    }

    #[TestDox('it can get credits')]
    public function testCredits(): void
    {
        $tale = Tale::factory()->withoutRelations()->createOne();

        $artists = Artist::factory(6)->create();

        $tale->credits()->attach([
            $artists[0]->id => ['type' => CreditType::Text, 'nr' => 2],
            $artists[1]->id => ['type' => CreditType::Text, 'nr' => 1],
            $artists[2]->id => ['type' => CreditType::Text, 'nr' => 0],
            $artists[3]->id => ['type' => CreditType::Music, 'nr' => 1],
            $artists[4]->id => ['type' => CreditType::Music, 'nr' => 0],
            $artists[5]->id => ['type' => CreditType::Music, 'nr' => 2],
        ]);

        $credits = $tale->fresh()->credits;
        $this->assertCount(6, $credits);
        $this->assertSameModel($artists[2], $credits[0]);
        $this->assertSameModel($artists[4], $credits[1]);
        $this->assertSameModel($artists[1], $credits[2]);
        $this->assertSameModel($artists[3], $credits[3]);
        $this->assertSameModel($artists[0], $credits[4]);
        $this->assertSameModel($artists[5], $credits[5]);
    }

    #[TestDox('it can get credits of given type')]
    public function testCreditsOfType(): void
    {
        $tale = Tale::factory()->withoutRelations()->createOne();

        $artists = Artist::factory(7)->create();

        $tale->credits()->attach([
            $artists[0]->id => ['type' => CreditType::Text, 'nr' => 2],
            $artists[1]->id => ['type' => CreditType::Text, 'nr' => 1],
            $artists[2]->id => ['type' => CreditType::Music, 'nr' => 1],
            $artists[3]->id => ['type' => CreditType::Music, 'nr' => 0],
            $artists[4]->id => ['type' => CreditType::Text, 'nr' => 0],
            $artists[5]->id => ['type' => CreditType::Music, 'nr' => 2],
            $artists[6]->id => ['type' => CreditType::Directing, 'nr' => 0],
        ]);

        $credits = $tale->fresh()->creditsFor(CreditType::Music);
        $this->assertCount(3, $credits);
        $this->assertSameModel($artists[3], $credits[0]);
        $this->assertSameModel($artists[2], $credits[1]);
        $this->assertSameModel($artists[5], $credits[2]);
    }

    #[TestDox('it can sync credits')]
    public function testSyncCredits(): void
    {
        $creditKeys = fn (Artist $artist) => Arr::only(
            $artist->credit->getAttributes(),
            ['type', 'as', 'nr'],
        );

        /**
         * @var Artist $firstArtist
         * @var Artist $secondArtist
         */
        [$firstArtist, $secondArtist] = Artist::factory(2)->create();

        $tale = Tale::factory()->withoutRelations()->createOne();

        // no credits -> one credit

        $tale->syncCredits([
            $firstArtist->id => [
                new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
            ],
        ]);

        $tale->refresh();

        $this->assertCount(1, $tale->credits);

        $this->assertSameModel($firstArtist, $tale->credits[0]);
        $this->assertSame(
            ['type' => 'directing', 'as' => 'Reżyserya', 'nr' => 0],
            $creditKeys($tale->credits[0]),
        );

        // one credit -> two credits for the same artist

        $tale->syncCredits([
            $firstArtist->id => [
                new CreditData(type: 'adaptation', as: null, nr: 1),
                new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
            ],
        ]);

        $tale->refresh();

        $this->assertCount(2, $tale->credits);

        $this->assertSameModel($firstArtist, $tale->credits[0]);
        $this->assertSame(
            ['type' => 'directing', 'as' => 'Reżyserya', 'nr' => 0],
            $creditKeys($tale->credits[0]),
        );

        $this->assertSameModel($firstArtist, $tale->credits[1]);
        $this->assertSame(
            ['type' => 'adaptation', 'as' => null, 'nr' => 1],
            $creditKeys($tale->credits[1]),
        );

        // two credits for the same artist -> two credits for two artists

        $tale->syncCredits([
            $firstArtist->id => [
                new CreditData(type: 'adaptation', as: null, nr: 1),
            ],
            $secondArtist->id => [
                new CreditData(type: 'directing', as: 'Reżyserya', nr: 0),
            ],
        ]);

        $tale->refresh();

        $this->assertCount(2, $tale->credits);

        $this->assertSameModel($secondArtist, $tale->credits[0]);
        $this->assertSame(
            ['type' => 'directing', 'as' => 'Reżyserya', 'nr' => 0],
            $creditKeys($tale->credits[0]),
        );

        $this->assertSameModel($firstArtist, $tale->credits[1]);
        $this->assertSame(
            ['type' => 'adaptation', 'as' => null, 'nr' => 1],
            $creditKeys($tale->credits[1]),
        );

        // two credits for the same artist -> change only credit attributes

        $tale->syncCredits([
            $firstArtist->id => [
                new CreditData(type: 'author', as: 'A-utor', nr: 2),
            ],
            $secondArtist->id => [
                new CreditData(type: 'music', as: null, nr: 3),
            ],
        ]);

        $tale->refresh();

        $this->assertCount(2, $tale->credits);

        $this->assertSameModel($firstArtist, $tale->credits[0]);
        $this->assertSame(
            ['type' => 'author', 'as' => 'A-utor', 'nr' => 2],
            $creditKeys($tale->credits[0]),
        );

        $this->assertSameModel($secondArtist, $tale->credits[1]);
        $this->assertSame(
            ['type' => 'music', 'as' => null, 'nr' => 3],
            $creditKeys($tale->credits[1]),
        );
    }

    #[TestDox('withActorsPopularity scope works')]
    public function testWithActorsPopularity(): void
    {
        $artists = Artist::factory(3)->create();

        $artists[0]->asActor()->attach(
            $ids = Tale::factory(6)->create()->map->id,
        );

        $artists[1]->asActor()->attach(
            Tale::factory(3)->create()->map->id,
        );

        $artists[1]->asActor()->attach($ids[0]);

        $tale = Tale::factory()->withoutRelations()->createOne();

        $tale->actors()->attach($artists->map->id);

        $this->assertSame(13, Tale::withActorsPopularity()->find($tale->id)->popularity);
    }
}
