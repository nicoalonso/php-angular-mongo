<?php

namespace App\Tests\Domain\Identity\Exception;

use App\Domain\Identity\Exception\CollectionMismatchException;
use App\Domain\Identity\Valet;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CollectionMismatchExceptionTest extends TestCase
{
    public function testShouldRunWhenValueAsNull(): void
    {
        $exception = new CollectionMismatchException('string', null);

        self::assertEquals('Value must be of type string; value is NULL', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsBool(): void
    {
        $exception = new CollectionMismatchException('string', false);

        self::assertEquals('Value must be of type string; value is FALSE', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsArray(): void
    {
        $exception = new CollectionMismatchException('string', []);

        self::assertEquals('Value must be of type string; value is Array', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsScalar(): void
    {
        $exception = new CollectionMismatchException('string', 12345);

        self::assertEquals('Value must be of type string; value is 12345', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsResource(): void
    {
        $handle = fopen("php://stdout", "w");
        $exception = new CollectionMismatchException('string', $handle);

        self::assertStringContainsString('Value must be of type string; value is (stream resource', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsObject(): void
    {
        $obj = (object)['abc' => 1];
        $exception = new CollectionMismatchException('string', $obj);

        self::assertEquals('Value must be of type string; value is (stdClass Object)', $exception->getMessage());
    }

    public function testShouldRunWhenValueAsDateTime(): void
    {
        $obj = new DateTimeImmutable('2023-05-30T15:00+02:00');
        $exception = new CollectionMismatchException('string', $obj);

        self::assertEquals(
            'Value must be of type string; value is (2023-05-30T15:00:00+02:00 DateTime)',
            $exception->getMessage()
        );
    }

    public function testShouldRunWhenValueAsState(): void
    {
        $obj = new Valet();
        $exception = new CollectionMismatchException('string', $obj);

        self::assertEquals(
            'Value must be of type string; value is (App\Domain\Identity\Valet Object)',
            $exception->getMessage()
        );
    }
}
