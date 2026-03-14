<?php

namespace App\Tests\Domain\Identity\List\Exception;

use App\Domain\Identity\List\Exception\InvalidSortFieldException;
use PHPUnit\Framework\TestCase;

class InvalidSortFieldExceptionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $exception = new InvalidSortFieldException('name');

        self::assertEquals('Invalid Sort Field Name: name', $exception->getMessage());
    }
}
