<?php

use App\Services\Wikipedia;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
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
                    'extract' => "<p><b>Piotr Fronczewski</b> (ur. 8 czerwca 1946 w Łodzi) – polski aktor teatralny, filmowy i dubbingowy, piosenkarz, satyryk, reżyser teatralny oraz pedagog. \n</p><p>Ma w swoim dorobku artystycznym blisko 120 ról filmowych w serialach telewizyjnych i filmach fabularnych. Jeden z najwybitniejszych i najwszechstronniejszych aktorów w historii polskiej kinematografii. Przez Gustawa Holoubka, Tadeusza Łomnickiego i Zbigniewa Zapasiewicza został w 1990 uznany za jednego z trzech największych polskich aktorów dramatycznych po 1965 (obok Wojciecha Pszoniaka i Andrzeja Seweryna).\n</p>",
                ],
            ],
        ],
    ];

    $this->extract = "Piotr Fronczewski (ur. 8 czerwca 1946 w Łodzi) – polski aktor teatralny, filmowy i dubbingowy, piosenkarz, satyryk, reżyser teatralny oraz pedagog. \nMa w swoim dorobku artystycznym blisko 120 ról filmowych w serialach telewizyjnych i filmach fabularnych. Jeden z najwybitniejszych i najwszechstronniejszych aktorów w historii polskiej kinematografii. Przez Gustawa Holoubka, Tadeusza Łomnickiego i Zbigniewa Zapasiewicza został w 1990 uznany za jednego z trzech największych polskich aktorów dramatycznych po 1965 (obok Wojciecha Pszoniaka i Andrzeja Seweryna).";
});

test('alias is properly registered', function () {
    expect(app(Wikipedia::class))->toBe(app('wikipedia'));
});

it('can create page url', function () {
    expect(app('wikipedia')->url('Joanna_Sobieska'))
        ->toBe('https://pl.wikipedia.org/wiki/Joanna_Sobieska');
});

it('can get extract from wikipedia', function () {
    Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

    expect(app('wikipedia')->extract('Piotr_Fronczewski'))->toBe($this->extract);

    Http::assertSent(
        fn ($request) => $request->url() === 'https://pl.wikipedia.org/w/api.php?action=query&titles=Piotr_Fronczewski&prop=extracts&exintro=1&redirects=1&format=json',
    );
});

it('caches wikipedia extract', function () {
    Http::fake();

    Cache::shouldReceive('remember')->once()
        ->with(
            'wikipedia-c562333d77f2c81b6f75acd8bd7c7871-extract',
            CarbonInterval::class, Closure::class,
        )
        ->andReturn($this->extract);

    expect(app('wikipedia')->extract('Piotr_Fronczewski'))->toBe($this->extract);

    Http::assertSentCount(0);
});

it('can refresh cached data', function () {
    $newResponse = [
        'query' => [
            'pages' => [
                117656 => ['extract' => 'test'],
            ],
        ],
    ];

    Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

    $wikipedia = app('wikipedia');

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe($this->extract);

    Facade::clearResolvedInstance(Illuminate\Http\Client\Factory::class);

    Http::fake(['pl.wikipedia.org/*' => Http::response($newResponse)]);

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe($this->extract);

    $wikipedia->refreshCache('Piotr_Fronczewski');

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe('test');
});

it('can flush cached data', function () {
    $newResponse = [
        'query' => [
            'pages' => [
                117656 => ['extract' => 'test'],
            ],
        ],
    ];

    Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

    $wikipedia = app('wikipedia');

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe($this->extract);

    Facade::clearResolvedInstance(Illuminate\Http\Client\Factory::class);

    Http::fake(['pl.wikipedia.org/*' => Http::response($newResponse)]);

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe($this->extract);

    expect($wikipedia->forget('Piotr_Fronczewski'))->toBeTrue();

    expect($wikipedia->extract('Piotr_Fronczewski'))->toBe('test');
});
