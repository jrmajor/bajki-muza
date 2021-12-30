<?php

namespace Tests\Feature\Artists;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewArtistsIndexTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this->get('artysci')->assertOk()->assertSeeLivewire('artists');
    }
}
