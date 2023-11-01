<?php

namespace Tests\Unit\Services;

use App\Services\FilmPolski;
use App\Values\FilmPolski\PhotoGroup;
use Carbon\CarbonInterval;
use Closure;
use Generator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

use function Tests\fixture;
use function Tests\read_fixture;

final class FilmPolskiTest extends TestCase
{
    #[TestDox('alias is properly registered')]
    public function testAlias(): void
    {
        $this->assertSame(app(FilmPolski::class), app(FilmPolski::class));
    }

    #[TestDox('it can create artist url')]
    public function testArtistUrl(): void
    {
        $this->assertSame(
            'http://www.filmpolski.pl/fp/index.php?osoba=112891',
            app(FilmPolski::class)->url(112891),
        );
    }

    /**
     * @param list<PhotoGroup> $expectedOutput
     */
    #[DataProvider('provideFilmPolskiCases')]
    #[TestDox('it can get photos from filmpolski')]
    public function testPhotos(
        int $personId,
        string $personSource,
        ?int $galleryId,
        ?string $gallerySource,
        array $expectedOutput,
    ): void {
        Http::fakeSequence()
            ->push($personSource)
            ->push($gallerySource);

        $photos = app(FilmPolski::class)->photos($personId);

        $this->assertCount(count($expectedOutput), $photos);

        foreach ($expectedOutput as $key => $movie) {
            $this->assertSame($movie->title, $photos[$key]->title);
            $this->assertSame($movie->year, $photos[$key]->year);
            $this->assertSame($movie->photos, $photos[$key]->photos);
        }

        Http::assertSentCount($galleryId === null ? 1 : 2);
    }

    #[TestDox('it caches filmpolski photos')]
    public function testPhotosCache(): void
    {
        $images = [new PhotoGroup(null, null, ['test'])];

        Cache::shouldReceive('remember')
            ->with('filmpolski-11232-photos', CarbonInterval::class, Closure::class)
            ->andReturn($images)
            ->once();

        $this->assertSame($images, app(FilmPolski::class)->photos(11232));

        Http::assertSentCount(0);
    }

    #[TestDox('it can flush cached data')]
    public function testFlushCache(): void
    {
        $filmPolski = app(FilmPolski::class);

        $this->assertFalse($filmPolski->forget(11232));

        Http::fake();

        $this->assertSame([], $filmPolski->photos(11232));

        $this->assertTrue($filmPolski->forget(11232));
    }

    /**
     * @return Generator<string, array{int, string, ?int, ?string, list<PhotoGroup>}>
     */
    public static function provideFilmPolskiCases(): Generator
    {
        // with photo and gallery
        yield 'Bogusz Bilewski' => [
            11232, read_fixture('FilmPolski/11232/osoba.html'),
            14232, read_fixture('FilmPolski/11232/galeria_osoby.html'),
            require fixture('FilmPolski/11232/photos.php'),
        ];

        // with photo and gallery
        yield 'Kalina Jędrusik' => [
            111707, read_fixture('FilmPolski/111707/osoba.html'),
            141707, read_fixture('FilmPolski/111707/galeria_osoby.html'),
            require fixture('FilmPolski/111707/photos.php'),
        ];

        // with no photo and no gallery
        yield 'Kalina Cyz' => [
            11178011, read_fixture('FilmPolski/11178011/osoba.html'),
            null, null, [],
        ];

        // with photo and no gallery
        yield 'Maria Gierszanin' => [
            116397, read_fixture('FilmPolski/116397/osoba.html'),
            null, null,
            require fixture('FilmPolski/116397/photos.php'),
        ];

        // with no photo and gallery
        yield 'Kamil Maria Banasiak' => [
            11148285, read_fixture('FilmPolski/11148285/osoba.html'),
            14148285, read_fixture('FilmPolski/11148285/galeria_osoby.html'),
            require fixture('FilmPolski/11148285/photos.php'),
        ];
    }
}
