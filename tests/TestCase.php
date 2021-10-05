<?php

namespace Tests;

use App\Images\Jobs\ProcessesImages;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\TemporaryDirectory\TemporaryDirectory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asUser(?User $user = null, ?string $driver = null): TestCase
    {
        return $this->actingAs($user ?? User::factory()->create(), $driver);
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
}
