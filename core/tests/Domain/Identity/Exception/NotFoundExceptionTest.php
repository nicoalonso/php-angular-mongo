<?php

namespace App\Tests\Domain\Identity\Exception;

use App\Domain\Identity\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class NotFoundExceptionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $exception = new NotFoundException();

        self::assertEquals('Object not found', $exception->getMessage());
    }
}
