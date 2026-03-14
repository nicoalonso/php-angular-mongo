<?php

namespace App\Tests\Doubles;

use BadMethodCallException;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertEquals;

trait Spyable
{
    /** @var array<string, int> */
    public array $spies = [];

    public function spy(): void
    {
        list (, $last) = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $method = $last['function'];

        if (array_key_exists($method, $this->spies)) {
            $this->spies[$method]++;
        } else {
            $this->spies[$method] = 1;
        }
    }
}

/**
 * @var Spyable $spy
 */
function assertSpy(mixed $spy, string $method, ?int $count = null): void
{
    if (!property_exists($spy, 'spies')) {
        throw new BadMethodCallException('Only can use in classes with Spyable trait');
    }

    assertArrayHasKey($method, $spy->spies, "Method $method was not called");

    if (null !== $count) {
        assertEquals(
            $count,
            $spy->spies[$method],
            "Method $method was called {$spy->spies[$method]} times, expected $count"
        );
    }
}

function assertNotSpy(mixed $spy, string $method): void
{
    if (!property_exists($spy, 'spies')) {
        throw new BadMethodCallException('Only can use in classes with Spyable trait');
    }

    assertArrayNotHasKey($method, $spy->spies, "Method $method was called");
}

function makeNullLogger(): Logger
{
    return new Logger('test', [new NullHandler()]);
}
