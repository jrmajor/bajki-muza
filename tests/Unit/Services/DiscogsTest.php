<?php

namespace Tests\Unit\Services;

use App\Services\Discogs;
use App\Values\Discogs\DiscogsPhoto;
use App\Values\Discogs\DiscogsPhotos;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Mockery;
use PHPUnit\Framework\Attributes\TestDox;
use Psl\Str;
use Tests\TestCase;

final class DiscogsTest extends TestCase
{
    #[TestDox('alias is properly registered')]
    public function testAlias(): void
    {
        $this->assertSame(app(Discogs::class), app(Discogs::class));
    }

    #[TestDox('it can create artist url')]
    public function testArtistUrl(): void
    {
        $this->assertSame(
            'https://www.discogs.com/artist/518243',
            app(Discogs::class)->url(518243),
        );
    }

    #[TestDox('it can create release url')]
    public function testReleaseUrl(): void
    {
        $this->assertSame(
            'https://www.discogs.com/release/2792351',
            app(Discogs::class)->releaseUrl(2792351),
        );
    }

    #[TestDox('it can get photos from discogs')]
    public function testPhotos(): void
    {
        config(['services.discogs.token' => '4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR']);

        Http::fake(['api.discogs.com/*' => Http::response($this->getSampleApiResponse())]);

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.discogs.com/artists/602473'
                && $request->hasHeader('Authorization', 'Discogs token=4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR');
        });
    }

    #[TestDox('it caches discogs photos')]
    public function testPhotosCache(): void
    {
        Cache::shouldReceive('flexible')
            ->with('discogs-602473-photos', Mockery::any(), Mockery::any())
            ->andReturn($this->getSampleApiResponse())
            ->once();

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        Http::assertSentCount(0);
    }

    #[TestDox('it can refresh cached data')]
    public function testRefreshCache(): void
    {
        $newResponse = [
            'images' => [
                [
                    'type' => 'primary',
                    'uri' => 'newTest',
                    'resource_url' => 'newTest',
                    'uri150' => 'newTest150',
                    'width' => 561,
                    'height' => 800,
                ],
            ],
        ];

        Http::fake(['api.discogs.com/*' => Http::response($this->getSampleApiResponse())]);

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        /** @phpstan-ignore property.notFound, argument.templateType */
        invade(Http::getFacadeRoot())->stubCallbacks = collect();

        Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        app(Discogs::class)->refreshCache(602473);

        $photos = app(Discogs::class)->photos(602473);

        $this->assertCount(1, $photos);
        $this->assertNotNull($photos->primary());
        $this->assertCount(0, $photos->secondary());
        $this->comparePhoto(new DiscogsPhoto(true, 'newTest', 'newTest150', 561, 800), $photos->primary());
    }

    #[TestDox('it can flush cached data')]
    public function testFlushCache(): void
    {
        $newResponse = [
            'images' => [
                [
                    'type' => 'primary',
                    'uri' => 'newTest',
                    'resource_url' => 'newTest',
                    'uri150' => 'newTest150',
                    'width' => 561,
                    'height' => 800,
                ],
            ],
        ];

        Http::fake(['api.discogs.com/*' => Http::response($this->getSampleApiResponse())]);

        $discogs = app(Discogs::class);

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        /** @phpstan-ignore property.notFound, argument.templateType */
        invade(Http::getFacadeRoot())->stubCallbacks = collect();

        Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

        $this->comparePhotos(app(Discogs::class)->photos(602473));

        $this->assertTrue($discogs->forget(602473));

        $photos = app(Discogs::class)->photos(602473);

        $this->assertCount(1, $photos);
        $this->assertNotNull($photos->primary());
        $this->assertCount(0, $photos->secondary());
        $this->comparePhoto(new DiscogsPhoto(true, 'newTest', 'newTest150', 561, 800), $photos->primary());
    }

    private function comparePhotos(DiscogsPhotos $photos): void
    {
        $this->assertCount(2, $photos);

        $this->assertNotNull($photos->primary());
        $this->assertCount(1, $photos->secondary());

        $this->comparePhoto(
            new DiscogsPhoto(true, $this->getSampleUrl(true), $this->getSampleUrl(true, true), 561, 800),
            $photos->primary(),
        );

        $this->comparePhoto(
            new DiscogsPhoto(false, $this->getSampleUrl(false), $this->getSampleUrl(false, true), 500, 332),
            $photos->secondary()[0],
        );
    }

    private function comparePhoto(DiscogsPhoto $expected, DiscogsPhoto $actual): void
    {
        $this->assertSame($expected->primary, $actual->primary);
        $this->assertSame($expected->uri, $actual->uri);
        $this->assertSame($expected->thumbUri, $actual->thumbUri);
        $this->assertSame($expected->width, $actual->width);
        $this->assertSame($expected->height, $actual->height);
    }

    /**
     * @return array<string, mixed>
     */
    private function getSampleApiResponse(): array
    {
        return [
            'name' => 'Piotr Fronczewski',
            'id' => 602473,
            'resource_url' => 'https://api.discogs.com/artists/602473',
            'uri' => 'https://www.discogs.com/artist/602473-Piotr-Fronczewski',
            'images' => [
                [
                    'type' => 'primary',
                    'uri' => $this->getSampleUrl(true),
                    'resource_url' => $this->getSampleUrl(true),
                    'uri150' => $this->getSampleUrl(true, true),
                    'width' => 561,
                    'height' => 800,
                ],
                [
                    'type' => 'secondary',
                    'uri' => $this->getSampleUrl(false),
                    'resource_url' => $this->getSampleUrl(false),
                    'uri150' => $this->getSampleUrl(false, true),
                    'width' => 500,
                    'height' => 332,
                ],
            ],
        ];
    }

    private function getSampleUrl(bool $primary, bool $thumbnail = false): string
    {
        $data = match ([$primary, $thumbnail]) {
            [true, false] => ['AUSKkwtZG3xVBRviuMp6vecR9Mg=/561x800', '90', '1566780713-1287'],
            [true, true] => ['dxKC1bXIzla9_XOD5YBUQC7xMgI=/150x150', '40', '1566780713-1287'],
            [false, false] => ['6UpFgK42Ii8QBuX-hc6Dh3gmSm0=/500x332', '90', '1187353511'],
            [false, true] => ['BpnX0ilhXYwsudiWfG_heikTqW0=/150x150', '40', '1187353511'],
        };

        return Str\format('https://img.discogs.com/%s/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(%s)/discogs-images/A-602473-%s.jpeg.jpg', ...$data);
    }
}
