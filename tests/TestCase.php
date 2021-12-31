<?php

namespace Tests;

use App\Images\Jobs\ProcessesImages;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\PendingCommand;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\IsInstanceOf;
use Psl\Type;
use Spatie\TemporaryDirectory\TemporaryDirectory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function asUser(?User $user = null, ?string $driver = null): TestCase
    {
        return $this->actingAs($user ?? User::factory()->create(), $driver);
    }

    public static function assertSameModel($expected, Model $actual): void
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

    public function imageProcessor(Closure $callback)
    {
        return (new class ($this) {
            use ProcessesImages;

            public function __construct(
                protected TestCase $testCase,
            ) { }

            public function test(Closure $callback)
            {
                $this->temporaryDirectory = (new TemporaryDirectory())->create();

                $callback->call($this, $this->testCase);

                $this->temporaryDirectory->delete();
            }
        })->test($callback);
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
