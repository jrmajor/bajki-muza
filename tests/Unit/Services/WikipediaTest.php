<?php

use Carbon\CarbonInterval;
use Facades\App\Services\Wikipedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->extract = "<p><b>Piotr Fronczewski</b> (ur. 8 czerwca 1946 w Łodzi) – polski aktor teatralny, filmowy i dubbingowy, piosenkarz, satyryk, reżyser teatralny oraz pedagog. \n</p><p>Ma w swoim dorobku artystycznym blisko 120 ról filmowych w serialach telewizyjnych i filmach fabularnych. Jeden z najwybitniejszych i najwszechstronniejszych aktorów w historii polskiej kinematografii. Przez Gustawa Holoubka, Tadeusza Łomnickiego i Zbigniewa Zapasiewicza został w 1990 uznany za jednego z trzech największych polskich aktorów dramatycznych po 1965 (obok Wojciecha Pszoniaka i Andrzeja Seweryna).\n</p>";

    $this->response = [
        'batchcomplete' => '',
        'warnings' => [
            'extracts' => [
                '*' => 'HTML may be malformed and/or unbalanced and may omit inline images. Use at your own risk. Known problems are listed at https://www.mediawiki.org/wiki/Special:MyLanguage/Extension:TextExtracts#Caveats.',
            ],
        ],
        'query' => [
            'normalized' => [
                [
                    'from' => 'Piotr_Fronczewski',
                    'to' => 'Piotr Fronczewski',
                ],
            ],
            'pages' => [
                117656 => [
                    'pageid' => 117656,
                    'ns' => 0,
                    'title' => 'Piotr Fronczewski',
                    'extract' => $this->extract,
                ],
            ],
        ],
    ];
});

it('can create page url', function () {
    expect(Wikipedia::url('Joanna_Sobieska'))
        ->toBe('https://pl.wikipedia.org/wiki/Joanna_Sobieska');
});

it('can get extract from wikipedia', function () {
    Http::fake([
        'pl.wikipedia.org/*' => Http::response($this->response),
    ]);

    expect(Wikipedia::extract('Piotr_Fronczewski'))->toBe($this->extract);

    Http::assertSent(
        fn ($request) => $request->url() === 'https://pl.wikipedia.org/w/api.php?action=query&titles=Piotr_Fronczewski&prop=extracts&exintro=1&redirects=1&format=json'
    );
});

it('caches wikipedia extract', function () {
    Http::fake();

    Cache::shouldReceive('remember')
        ->once()
        ->with(
            'wikipedia-c562333d77f2c81b6f75acd8bd7c7871-extract',
            CarbonInterval::class,
            Closure::class,
        )->andReturn($this->extract);

    expect(Wikipedia::extract('Piotr_Fronczewski'))->toBe($this->extract);

    Http::assertSentCount(0);
});

it('can flush cached data', function () {
    $newResponse = [
        'query' => [
            'pages' => [
                117656 => ['extract' => 'test'],
            ],
        ],
    ];

    Http::fake([
        'pl.wikipedia.org/*' => Http::response($this->response),
    ]);

    expect(Wikipedia::extract('Piotr_Fronczewski'))->toBe($this->extract);

    Http::clearResolvedInstances();

    Http::fake([
        'pl.wikipedia.org/*' => Http::response($newResponse),
    ]);

    expect(Wikipedia::extract('Piotr_Fronczewski'))->toBe($this->extract);

    expect(Wikipedia::forget('Piotr_Fronczewski'))->toBeTrue();

    expect(Wikipedia::extract('Piotr_Fronczewski'))->toBe('test');
});
