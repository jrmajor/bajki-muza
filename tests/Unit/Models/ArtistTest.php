<?php

namespace Tests\Unit\Models;

use App\Images\Photo;
use App\Images\Values\ArtistPhotoCrop;
use App\Models\Artist;
use App\Models\Tale;
use App\Services\Discogs;
use App\Services\FilmPolski;
use App\Services\Wikipedia;
use App\Values\CreditType;
use App\Values\Discogs\DiscogsPhoto;
use App\Values\Discogs\DiscogsPhotos;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ArtistTest extends TestCase
{
    #[TestDox('it casts discogs id to integer')]
    public function testCastDiscogs(): void
    {
        $this->assertSame(1023394, (new Artist(['discogs' => '1023394']))->discogs);
    }

    #[TestDox('it casts filmpolski id to integer')]
    public function testCastFilmPolski(): void
    {
        $this->assertSame(116251, (new Artist(['filmpolski' => '116251']))->filmpolski);
    }

    #[TestDox('it generates slug when created')]
    public function testSlugCreate(): void
    {
        $artist = Artist::create(['name' => 'Tadeusz Włudarski']);

        $this->assertSame('tadeusz-wludarski', $artist->slug);

        $artist = new Artist();
        $artist->name = 'Zofia Rysiówna';

        $this->assertNull($artist->slug);

        $artist->save();

        $this->assertSame('zofia-rysiowna', $artist->slug);
    }

    #[TestDox('it regenerates slug when updated')]
    public function testSlugUpdate(): void
    {
        $artist = Artist::create(['name' => 'Tadeusz Włudarski']);

        $this->assertSame('tadeusz-wludarski', $artist->slug);

        $artist->name = 'Zofia Rysiówna';

        $artist->save();

        $this->assertSame('zofia-rysiowna', $artist->slug);

        $artist->fill(['name' => 'Andrzej Stockinger'])->save();

        $this->assertSame('andrzej-stockinger', $artist->slug);
    }

    #[TestDox('it can get its photo')]
    public function testPhoto(): void
    {
        $photo = Photo::create([
            'filename' => 'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
            'crop' => ArtistPhotoCrop::fake(),
        ]);

        $artist = Artist::factory()->photo($photo)->createOne();

        $this->assertInstanceOf(Photo::class, $artist->photo);
        $this->assertSame(
            'tXySLaaEbhfyzLXm6QggZY5VSFulyN2xLp4OgYSy.png',
            $artist->photo->filename(),
        );
    }

    #[TestDox('it can generate discogs url')]
    public function testDiscogsUrl(): void
    {
        $artist = Artist::factory()->makeOne(['discogs' => 518243]);

        $this->mock(Discogs::class)
            ->shouldReceive('url')
            ->andReturn('https://www.discogs.com/artist/518243');

        $this->assertSame('https://www.discogs.com/artist/518243', $artist->discogs_url);
    }

    #[TestDox('it can generate filmpolski url')]
    public function testFilmPolskiUrl(): void
    {
        $artist = Artist::factory()->makeOne(['filmpolski' => 112891]);

        $this->mock(FilmPolski::class)
            ->shouldReceive('url')
            ->andReturn('http://www.filmpolski.pl/fp/index.php?osoba=112891');

        $this->assertSame('http://www.filmpolski.pl/fp/index.php?osoba=112891', $artist->filmpolski_url);
    }

    #[TestDox('it can generate wikipedia url')]
    public function testWikipediaUrl(): void
    {
        $artist = Artist::factory()->makeOne(['wikipedia' => 'Joanna_Sobieska']);

        $this->mock(Wikipedia::class)
            ->shouldReceive('url')
            ->andReturn('https://pl.wikipedia.org/wiki/Joanna_Sobieska');

        $this->assertSame('https://pl.wikipedia.org/wiki/Joanna_Sobieska', $artist->wikipedia_url);
    }

    #[TestDox('it does not generate nonexistent urls')]
    public function testNonexistentUrls(): void
    {
        $artist = Artist::factory()->makeOne([
            'discogs' => null,
            'filmpolski' => null,
            'wikipedia' => null,
        ]);


        $this->assertNull($artist->discogs_url);
        $this->assertNull($artist->filmpolski_url);
        $this->assertNull($artist->wikipedia_url);
    }

    #[TestDox('it can get extract from wikipedia')]
    public function testWikipediaExtract(): void
    {
        $extract = 'Piotr Fronczewski (ur. 8 czerwca 1946 w Łodzi) – polski aktor.';

        $artist = Artist::factory()->createOne(['wikipedia' => 'Piotr_Fronczewski']);

        $this->mock(Wikipedia::class)
            ->shouldReceive('extract')
            ->with('Piotr_Fronczewski')
            ->andReturn($extract);

        $this->assertSame($extract, $artist->wikipedia_extract);
    }

    #[TestDox('it does not query wikipedia when no id is set')]
    public function testWikipediaNoExtract(): void
    {
        $artist = Artist::factory()->createOne(['wikipedia' => null]);

        $this->spy(Wikipedia::class)->shouldNotReceive('extract');

        $this->assertNull($artist->wikipedia_extract);
    }

    #[TestDox('it can get photos from discogs')]
    public function testDiscogsPhotos(): void
    {
        $photos = new DiscogsPhotos([
            new DiscogsPhoto(true, 'test', 'test150', 561, 800),
        ]);

        $artist = Artist::factory()->createOne(['discogs' => 602473]);

        $this->mock(Discogs::class)
            ->shouldReceive('photos')
            ->with(602473)
            ->andReturn($photos);

        $this->assertSame($photos, $artist->discogsPhotos());
    }

    #[TestDox('it does not query discogs when no id is set')]
    public function testDiscogsNoPhotos(): void
    {
        $artist = Artist::factory()->createOne(['discogs' => null]);

        $this->spy(Discogs::class)->shouldNotReceive('photos');

        $this->assertCount(0, $artist->discogsPhotos());
    }

    #[TestDox('it can get photos from filmpolski')]
    public function testFilmPolskiPhotos(): void
    {
        $images = [
            'main' => [
                'year' => null,
                'photos' => ['test'],
            ],
        ];

        $artist = Artist::factory()->createOne(['filmpolski' => 112891]);

        $this->mock(FilmPolski::class)
            ->shouldReceive('photos')
            ->with(112891)
            ->andReturn($images);

        $this->assertSame($images, $artist->filmPolskiPhotos());
    }

    #[TestDox('it does not query filmpolski when no id is set')]
    public function testFilmPolskiNoPhotos(): void
    {
        $artist = Artist::factory()->createOne(['filmpolski' => null]);

        $this->spy(FilmPolski::class)->shouldNotReceive('photos');

        $this->assertCount(0, $artist->filmPolskiPhotos());
    }

    #[TestDox('it can get photo from discogs')]
    public function testDiscogsPhoto(): void
    {
        $photos = new DiscogsPhotos([
            new DiscogsPhoto(true, 'test', 'test150', 561, 800),
        ]);

        $artist = Artist::factory()->createOne(['discogs' => 602473]);

        $this->mock(Discogs::class)
            ->shouldReceive('photos')
            ->with(602473)
            ->andReturn($photos);

        $this->assertSame('test', $artist->discogsPhoto());
        $this->assertSame('test150', $artist->discogsPhoto('thumb'));
    }

    #[TestDox('it can get its appearances as actor')]
    public function testAsActor(): void
    {
        $artist = Artist::factory()->createOne();

        $this->assertInstanceOf(BelongsToMany::class, $artist->asActor());

        $tales = collect([
            Tale::factory()->createOne(['year' => 1978]),
            Tale::factory()->createOne(['year' => 1969, 'title' => 'b']),
            Tale::factory()->createOne(['year' => 1969, 'title' => 'a']),
        ]);

        $artist->asActor()->attach($tales->map->id);

        $asActor = $artist->fresh()->asActor;
        $this->assertCount(3, $asActor);
        $this->assertSameModel($tales[2], $asActor[0]);
        $this->assertSameModel($tales[1], $asActor[1]);
        $this->assertSameModel($tales[0], $asActor[2]);
    }

    #[TestDox('it can get credits')]
    public function testCredits(): void
    {
        $artist = Artist::factory()->createOne();

        $this->assertInstanceOf(BelongsToMany::class, $artist->credits());

        $tales = Tale::factory(6)->sequence(
            ['year' => 1979],
            ['year' => 1969, 'title' => 'b'],
            ['year' => 1969, 'title' => 'a'],
            ['year' => 1978],
            ['year' => 1969, 'title' => 'c'],
            ['year' => 1969, 'title' => 'd'],
        )->create();

        $lyricist = ['type' => CreditType::Text, 'nr' => 0];
        $composer = ['type' => CreditType::Music, 'nr' => 0];

        $artist->credits()->attach([
            $tales[0]->id => $lyricist,
            $tales[1]->id => $lyricist,
            $tales[2]->id => $lyricist,
            $tales[3]->id => $composer,
            $tales[4]->id => $composer,
            $tales[5]->id => $composer,
        ]);

        $credits = $artist->fresh()->credits;
        $this->assertCount(6, $credits);
        $this->assertSameModel($tales[2], $credits[0]);
        $this->assertSameModel($tales[1], $credits[1]);
        $this->assertSameModel($tales[4], $credits[2]);
        $this->assertSameModel($tales[5], $credits[3]);
        $this->assertSameModel($tales[3], $credits[4]);
        $this->assertSameModel($tales[0], $credits[5]);
    }

    #[TestDox('it can get credits of given type')]
    public function testCreditsOfType(): void
    {
        $artist = Artist::factory()->createOne();

        $tales = Tale::factory(4)->sequence(
            ['year' => 1978],
            ['year' => 1969, 'title' => 'b'],
            ['year' => 1978],
            ['year' => 1969, 'title' => 'a'],
        )->create();

        $lyricist = ['type' => CreditType::Text, 'nr' => 0];
        $composer = ['type' => CreditType::Music, 'nr' => 0];

        $artist->credits()->attach([
            $tales[0]->id => $lyricist,
            $tales[1]->id => $lyricist,
            $tales[2]->id => $composer,
            $tales[3]->id => $lyricist,
        ]);

        $credits = $artist->fresh()->creditsFor(CreditType::Text);
        $this->assertInstanceOf(EloquentCollection::class, $credits);
        $this->assertCount(3, $credits);
        $this->assertSameModel($tales[3], $credits[0]);
        $this->assertSameModel($tales[1], $credits[1]);
        $this->assertSameModel($tales[0], $credits[2]);
    }

    #[TestDox('countAppearances scope works')]
    public function testCountAppearances(): void
    {
        $artist = Artist::factory()->createOne();

        $artist->credits()->attach(
            Tale::factory(4)->create()->map->id,
            ['type' => CreditType::Music, 'nr' => 0],
        );

        $artist->asActor()->attach(
            Tale::factory(6)->create()->map->id,
        );

        $duplicate = Tale::factory()->createOne();

        $artist->credits()->attach(
            $duplicate,
            ['type' => CreditType::Directing, 'nr' => 0],
        );

        $artist->asActor()->attach($duplicate);

        $this->assertSame(11, Artist::countAppearances()->find($artist->id)->appearances);
    }

    #[TestDox('appearances method works')]
    public function testAppearances(): void
    {
        $artist = Artist::factory()->createOne();

        $artist->credits()->attach(
            Tale::factory(4)->create()->map->id,
            ['type' => CreditType::Music, 'nr' => 0],
        );

        $artist->asActor()->attach(
            Tale::factory(6)->create()->map->id,
        );

        $duplicate = Tale::factory()->createOne();

        $artist->credits()->attach(
            $duplicate,
            ['type' => CreditType::Directing, 'nr' => 0],
        );

        $artist->asActor()->attach($duplicate);

        $this->assertSame(11, $artist->appearances());
    }

    #[TestDox('it can refresh cached data')]
    public function testRefreshCache(): void
    {
        $artist = Artist::factory()->createOne([
            'discogs' => 602473,
            'filmpolski' => 112891,
            'wikipedia' => 'Piotr_Fronczewski',
        ]);

        $this->mock(Discogs::class)->shouldReceive('refreshCache')->with(602473)->andReturn(true);
        $this->mock(FilmPolski::class)->shouldReceive('refreshCache')->with(112891)->andReturn(true);
        $this->mock(Wikipedia::class)->shouldReceive('refreshCache')->with('Piotr_Fronczewski')->andReturn(true);

        $artist->refreshCache();
    }

    #[TestDox('it can flush cached data')]
    public function testFlushCache(): void
    {
        $artist = Artist::factory()->createOne([
            'discogs' => 602473,
            'filmpolski' => 112891,
            'wikipedia' => 'Piotr_Fronczewski',
        ]);

        $this->mock(Discogs::class)->shouldReceive('forget')->with(602473)->andReturn(true);
        $this->mock(FilmPolski::class)->shouldReceive('forget')->with(112891)->andReturn(true);
        $this->mock(Wikipedia::class)->shouldReceive('forget')->with('Piotr_Fronczewski')->andReturn(true);

        $this->assertTrue($artist->flushCache());
    }

    #[TestDox('findBySlug method works')]
    public function testFindBySlug(): void
    {
        $this->assertNull(Artist::findBySlug('Jan Matyjaszkiewicz'));

        $artist = Artist::factory()->createOne(['name' => 'Jan Matyjaszkiewicz']);

        $this->assertNull(Artist::findBySlug('Jan Matyjaszkiewicz'));

        $this->assertSameModel($artist, Artist::findBySlug('jan-matyjaszkiewicz'));
    }

    #[TestDox('findBySlugOrNew method works')]
    public function testFindBySlugOrNew(): void
    {
        $artist = Artist::factory()->createOne(['name' => 'Jan Matyjaszkiewicz']);

        $this->assertSame(1, Artist::count());

        $this->assertSameModel($artist, Artist::findBySlugOrNew(' jan matyjaSZkiewiCZ'));

        $this->assertSame(1, Artist::count());

        $artist = Artist::findBySlugOrNew('Jan Kobuszewski');

        $this->assertSame(2, Artist::count());

        $this->assertSame('Jan Kobuszewski', $artist->name);
        $this->assertSame('jan-kobuszewski', $artist->slug);
    }
}
