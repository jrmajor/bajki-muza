<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

final class InertiaSharedPropsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware('web')->get(
            'inertia-shared-props-test',
            fn () => Inertia::render('InertiaSharedPropsTest', []),
        );
    }

    #[TestDox('it shares errors and guest from request')]
    public function testShareGuest(): void
    {
        $r = $this->get('inertia-shared-props-test')->assertOk();

        $this->assertSame([
            'errors' => [],
            'user' => null,
        ], $r->inertiaProps());
    }

    #[TestDox('it shares errors and user from request')]
    public function testShareUser(): void
    {
        $r = $this
            ->asUser($user = User::factory()->createOne())
            ->get('inertia-shared-props-test')
            ->assertOk();

        $this->assertSame([
            'errors' => [],
            'user' => [
                'username' => $user->username,
                'id' => $user->id,
            ],
        ], $r->inertiaProps());
    }
}
