<?php

namespace App\Tests\Domain\Identity\List\Exception;

use App\Domain\Identity\List\Exception\InvalidFilterException;
use PHPUnit\Framework\TestCase;

class InvalidFilterExceptionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $exception = new InvalidFilterException('name');

        self::assertEquals('Invalid filter: name', $exception->getMessage());
    }
}
