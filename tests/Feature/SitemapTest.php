<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Tale;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class GenerateSitemapTest extends TestCase
{
    #[TestDox('it shows correct sitemap')]
    public function testSitemap(): void
    {
        $artists = Artist::factory(2)->create();
        $tales = Tale::factory(2)->create();

        $this->get('sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
            ->assertSee([
                '<loc>' . url('artysci') . '</loc>',
                '<loc>' . url("artysci/{$artists[0]->slug}") . '</loc>',
                '<loc>' . url("artysci/{$artists[1]->slug}") . '</loc>',
                '<loc>' . url('bajki') . '</loc>',
                '<loc>' . url("bajki/{$tales[0]->slug}") . '</loc>',
                '<loc>' . url("bajki/{$tales[1]->slug}") . '</loc>',
            ], false);
    }
}
