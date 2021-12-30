<?php

namespace Tests\Feature\Tales;

use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewTalesIndexTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $this->get('bajki')->assertOk()->assertSeeLivewire('tales');
    }
}
