<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestDox;
use Psl\Json;
use Psl\Regex;
use Tests\TestCase;

final class WelcomePageTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this->get('/')->assertRedirect('bajki');
    }

    #[TestDox('it exposes site name metadata')]
    public function testSiteNameMetadata(): void
    {
        $response = $this->get('bajki')
            ->assertOk()
            ->assertSeeHtml('<meta name="application-name" content="Bajki Polskich Nagrań „Muza”">')
            ->assertSeeHtml('<meta property="og:site_name" content="Bajki Polskich Nagrań „Muza”">');

        $content = $response->getContent();

        $this->assertIsString($content);

        $matches = Regex\first_match(
            $content,
            '/<script type="application\\/ld\\+json">\\s*(?<json>.*?)\\s*<\\/script>/s',
            Regex\capture_groups(['json']),
        );

        $this->assertNotNull($matches);

        $this->assertSame([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Bajki Polskich Nagrań „Muza”',
            'url' => url('/'),
        ], Json\decode($matches['json']));
    }
}
