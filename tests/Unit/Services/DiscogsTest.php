<?php

namespace Tests\Unit\Services;

use App\Services\Discogs;
use Carbon\CarbonInterval;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class DiscogsTest extends TestCase
{
    private array $photoResponse;

    private array $photos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->photoResponse = [
            'name' => 'Piotr Fronczewski',
            'id' => 602473,
            'resource_url' => 'https://api.discogs.com/artists/602473',
            'uri' => 'https://www.discogs.com/artist/602473-Piotr-Fronczewski',
            'images' => [
                [
                    'type' => 'primary',
                    'uri' => 'https://img.discogs.com/AUSKkwtZG3xVBRviuMp6vecR9Mg=/561x800/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1566780713-1287.jpeg.jpg',
                    'resource_url' => 'https://img.discogs.com/AUSKkwtZG3xVBRviuMp6vecR9Mg=/561x800/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1566780713-1287.jpeg.jpg',
                    'uri150' => 'https://img.discogs.com/dxKC1bXIzla9_XOD5YBUQC7xMgI=/150x150/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(40)/discogs-images/A-602473-1566780713-1287.jpeg.jpg',
                    'width' => 561,
                    'height' => 800,
                ],
                [
                    'type' => 'secondary',
                    'uri' => 'https://img.discogs.com/6UpFgK42Ii8QBuX-hc6Dh3gmSm0=/500x332/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1187353511.jpeg.jpg',
                    'resource_url' => 'https://img.discogs.com/6UpFgK42Ii8QBuX-hc6Dh3gmSm0=/500x332/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1187353511.jpeg.jpg',
                    'uri150' => 'https://img.discogs.com/BpnX0ilhXYwsudiWfG_heikTqW0=/150x150/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(40)/discogs-images/A-602473-1187353511.jpeg.jpg',
                    'width' => 500,
                    'height' => 332,
                ],
            ],
        ];

        $this->photos = [
            [
                'type' => 'primary',
                'uri' => 'https://img.discogs.com/AUSKkwtZG3xVBRviuMp6vecR9Mg=/561x800/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1566780713-1287.jpeg.jpg',
                'uri150' => 'https://img.discogs.com/dxKC1bXIzla9_XOD5YBUQC7xMgI=/150x150/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(40)/discogs-images/A-602473-1566780713-1287.jpeg.jpg',
                'width' => 561,
                'height' => 800,
            ],
            [
                'type' => 'secondary',
                'uri' => 'https://img.discogs.com/6UpFgK42Ii8QBuX-hc6Dh3gmSm0=/500x332/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(90)/discogs-images/A-602473-1187353511.jpeg.jpg',
                'uri150' => 'https://img.discogs.com/BpnX0ilhXYwsudiWfG_heikTqW0=/150x150/smart/filters:strip_icc():format(jpeg):mode_rgb():quality(40)/discogs-images/A-602473-1187353511.jpeg.jpg',
                'width' => 500,
                'height' => 332,
            ],
        ];
    }

    #[TestDox('alias is properly registered')]
    public function testAlias(): void
    {
        $this->assertSame(app('discogs'), app(Discogs::class));
    }

    #[TestDox('it can create artist url')]
    public function testArtistUrl(): void
    {
        $this->assertSame(
            'https://www.discogs.com/artist/518243',
            app('discogs')->url(518243),
        );
    }

    #[TestDox('it can create release url')]
    public function testReleaseUrl(): void
    {
        $this->assertSame(
            'https://www.discogs.com/release/2792351',
            app('discogs')->releaseUrl(2792351),
        );
    }

    #[TestDox('it can get photos from discogs')]
    public function testPhotos(): void
    {
        config(['services.discogs.token' => '4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR']);

        Http::fake(['api.discogs.com/*' => Http::response($this->photoResponse)]);

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.discogs.com/artists/602473'
                && $request->hasHeader('Authorization', 'Discogs token=4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR');
        });
    }

    #[TestDox('it caches discogs photos')]
    public function testPhotosCache(): void
    {
        Http::fake();

        Cache::shouldReceive('remember')->once()
            ->with('discogs-602473-photos', CarbonInterval::class, Closure::class)
            ->andReturn($this->photoResponse);

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

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

        Http::fake(['api.discogs.com/*' => Http::response($this->photoResponse)]);

        $discogs = app('discogs');

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

        Facade::clearResolvedInstance(\Illuminate\Http\Client\Factory::class);

        Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

        $discogs->refreshCache(602473);

        $this->assertSame([[
            'type' => 'primary',
            'uri' => 'newTest',
            'uri150' => 'newTest150',
            'width' => 561,
            'height' => 800,
        ]], $discogs->photos(602473)->toArray());
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

        Http::fake(['api.discogs.com/*' => Http::response($this->photoResponse)]);

        $discogs = app('discogs');

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

        Facade::clearResolvedInstance(\Illuminate\Http\Client\Factory::class);

        Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

        $this->assertSame($this->photos, app('discogs')->photos(602473)->toArray());

        $this->assertTrue($discogs->forget(602473));

        $this->assertSame([[
            'type' => 'primary',
            'uri' => 'newTest',
            'uri150' => 'newTest150',
            'width' => 561,
            'height' => 800,
        ]], $discogs->photos(602473)->toArray());
    }
}
