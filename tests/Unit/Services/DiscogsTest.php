<?php

use Carbon\CarbonInterval;
use Facades\App\Services\Discogs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
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
});

it('can create artist url', function () {
    expect(Discogs::url(518243))
        ->toBe('https://www.discogs.com/artist/518243');
});

it('can create release url', function () {
    expect(Discogs::releaseUrl(2792351))
        ->toBe('https://www.discogs.com/release/2792351');
});

it('can get photos from discogs', function () {
    config(['services.discogs.token' => '4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR']);

    Http::fake(['api.discogs.com/*' => Http::response($this->photoResponse)]);

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.discogs.com/artists/602473'
            && $request->hasHeader('Authorization', 'Discogs token=4AmTYWLl1H9PVkjZCsrXiQy0e75MMtXehoZdsvkR');
    });
});

it('caches discogs photos', function () {
    Http::fake();

    Cache::shouldReceive('remember')
        ->once()
        ->with(
            'discogs-602473-photos',
            CarbonInterval::class,
            Closure::class,
        )->andReturn($this->photoResponse);

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    Http::assertSentCount(0);
});

it('can refresh cached data', function () {
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

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    Facade::clearResolvedInstance(Illuminate\Http\Client\Factory::class);

    Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    Discogs::refreshCache(602473);

    expect(Discogs::photos(602473)->toArray())->toBe([[
        'type' => 'primary',
        'uri' => 'newTest',
        'uri150' => 'newTest150',
        'width' => 561,
        'height' => 800,
    ]]);
});

it('can flush cached data', function () {
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

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    Facade::clearResolvedInstance(Illuminate\Http\Client\Factory::class);

    Http::fake(['api.discogs.com/*' => Http::response($newResponse)]);

    expect(Discogs::photos(602473)->toArray())->toBe($this->photos);

    expect(Discogs::forget(602473))->toBeTrue();

    expect(Discogs::photos(602473)->toArray())->toBe([[
        'type' => 'primary',
        'uri' => 'newTest',
        'uri150' => 'newTest150',
        'width' => 561,
        'height' => 800,
    ]]);
});
