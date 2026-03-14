<?php

namespace App\Tests\Domain\Identity\Exception;

use App\Domain\Identity\Exception\AccessDeniedException;
use PHPUnit\Framework\TestCase;

class AccessDeniedExceptionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $exception = new AccessDeniedException();

        self::assertEquals('You do not have permissions to access', $exception->getMessage());
    }
}
