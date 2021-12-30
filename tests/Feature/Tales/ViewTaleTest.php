<?php

namespace Tests\Feature\Tales;

use App\Models\Tale;
use App\Services\Discogs;
use App\Services\Wikipedia;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class ViewTaleTest extends TestCase
{
    #[TestDox('it works')]
    public function testOk(): void
    {
        $tale = Tale::factory()->createOne();

        $this->mock(Wikipedia::class)->shouldReceive('extract')->andReturn('test');
        $this->mock(Discogs::class)->shouldReceive('photos')->andReturn(collect());

        $this->get("bajki/{$tale->slug}")->assertOk();
    }

    #[TestDox('returns 404 when attempting to view nonexistent tale')]
    public function test404(): void
    {
        $this->get('bajki/nonexistent-tale')->assertStatus(404);
    }
}
