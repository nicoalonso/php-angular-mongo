<?php

namespace App\Tests\Domain\Bus;

use App\Domain\Bus\DomainRoute;
use PHPUnit\Framework\TestCase;

class DomainRouteTest extends TestCase
{
    public function testShouldRunWhenHasRoute(): void
    {
        $routeNone = DomainRoute::NONE;
        $route = DomainRoute::LIBRARY;

        self::assertFalse($routeNone->has());
        self::assertTrue($route->has());
    }
}
