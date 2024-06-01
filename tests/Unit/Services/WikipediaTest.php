<?php

namespace Tests\Unit\Services;

use App\Services\Wikipedia;
use Carbon\CarbonInterval;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class WikipediaTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $response;

    private string $extract;

    protected function setUp(): void
    {
        parent::setUp();

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
    }

    #[TestDox('alias is properly registered')]
    public function testAlias(): void
    {
        $this->assertSame(app(Wikipedia::class), app(Wikipedia::class));
    }

    #[TestDox('it can create page url')]
    public function testPageUrl(): void
    {
        $this->assertSame(
            'https://pl.wikipedia.org/wiki/Joanna_Sobieska',
            app(Wikipedia::class)->url('Joanna_Sobieska'),
        );
    }

    #[TestDox('it can get extract from wikipedia')]
    public function testExtract(): void
    {
        Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

        $this->assertSame($this->extract, app(Wikipedia::class)->extract('Piotr_Fronczewski'));

        Http::assertSent(
            fn ($request) => $request->url() === 'https://pl.wikipedia.org/w/api.php?action=query&titles=Piotr_Fronczewski&prop=extracts&exintro=1&redirects=1&format=json',
        );
    }

    #[TestDox('it caches wikipedia extract')]
    public function testExtractCache(): void
    {
        Cache::shouldReceive('remember')->once()->with(
            'wikipedia-c562333d77f2c81b6f75acd8bd7c7871-extract',
            CarbonInterval::class, Closure::class,
        )->andReturn($this->extract);

        $this->assertSame($this->extract, app(Wikipedia::class)->extract('Piotr_Fronczewski'));

        Http::assertSentCount(0);
    }

    #[TestDox('it can refresh cached data')]
    public function testRefreshCache(): void
    {
        $newResponse = [
            'query' => [
                'pages' => [
                    117656 => ['extract' => 'test'],
                ],
            ],
        ];

        Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

        $wikipedia = app(Wikipedia::class);

        $this->assertSame($this->extract, $wikipedia->extract('Piotr_Fronczewski'));

        /** @phpstan-ignore property.notFound, argument.templateType */
        invade(Http::getFacadeRoot())->stubCallbacks = collect();

        Http::fake(['pl.wikipedia.org/*' => Http::response($newResponse)]);

        $this->assertSame($this->extract, $wikipedia->extract('Piotr_Fronczewski'));

        $wikipedia->refreshCache('Piotr_Fronczewski');

        $this->assertSame('test', $wikipedia->extract('Piotr_Fronczewski'));
    }

    #[TestDox('it can flush cached data')]
    public function testFlushCache(): void
    {
        $newResponse = [
            'query' => [
                'pages' => [
                    117656 => ['extract' => 'test'],
                ],
            ],
        ];

        Http::fake(['pl.wikipedia.org/*' => Http::response($this->response)]);

        $wikipedia = app(Wikipedia::class);

        $this->assertSame($this->extract, $wikipedia->extract('Piotr_Fronczewski'));

        /** @phpstan-ignore property.notFound, argument.templateType */
        invade(Http::getFacadeRoot())->stubCallbacks = collect();

        Http::fake(['pl.wikipedia.org/*' => Http::response($newResponse)]);

        $this->assertSame($this->extract, $wikipedia->extract('Piotr_Fronczewski'));

        $this->assertTrue($wikipedia->forget('Piotr_Fronczewski'));

        $this->assertSame('test', $wikipedia->extract('Piotr_Fronczewski'));
    }
}
