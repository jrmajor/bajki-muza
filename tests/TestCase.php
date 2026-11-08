<?php

namespace Tests;

use App\Models\User;
use Closure;
use Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\PendingCommand;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Psl\Type;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutDeprecationHandling();

        Http::preventStrayRequests();
    }

    public function asUser(?User $user = null, ?string $driver = null): self
    {
        return $this->actingAs($user ?? User::factory()->createOne(), $driver);
    }

    public static function assertSameModel(mixed $expected, Model $actual): void
    {
        self::assertThat($actual, Assert::logicalOr(
            new IsInstanceOf(Model::class),
            new IsInstanceOf(Relation::class),
        ));

        self::assertTrue(
            $actual->is($expected),
            'Value is not expected Eloquent model.',
        );
    }

    /**
     * @return Generator<string, array{Closure(): mixed}>
     */
    public static function provideTransientFailures(): Generator
    {
        yield 'connection error' => [fn () => Http::failedConnection()];

        yield 'server error' => [fn () => Http::response(status: 500)];

        yield 'rate limit' => [fn () => Http::response(status: 429)];
    }

    /**
     * @param non-empty-string $command
     */
    protected function a(string $command): PendingCommand
    {
        return Type\instance_of(PendingCommand::class)
            ->coerce($this->artisan($command));
    }
}
