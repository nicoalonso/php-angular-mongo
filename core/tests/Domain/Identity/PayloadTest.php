<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\Payload;
use PHPUnit\Framework\TestCase;

class DummyPayload extends Payload {}

class PayloadTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $payload = new DummyPayload(['name' => 'test']);

        self::assertEquals('test', $payload->getName());
        self::assertNull($payload->toValidId());
    }

    public function testShouldNullWhenGetInvalidId(): void
    {
        $payload = new DummyPayload(['id' => 'test']);

        self::assertNull($payload->toValidId());
    }

    public function testShouldRunWhenGetValidId(): void
    {
        $payload = new DummyPayload(['id' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479']);

        self::assertEquals('f47ac10b-58cc-4372-a567-0e02b2c3d479', $payload->toValidId());
    }

    public function testShouldEmptyWhenToList(): void
    {
        $payload = new DummyPayload(['id' => 'test']);

        self::assertEmpty($payload->toList('test'));
    }

    public function testShouldRunWhenHasArrayOnToList(): void
    {
        $payload = new DummyPayload(['test' => ['test', 'test', 'dummy', '']]);

        self::assertEquals(['test', 'dummy'], $payload->toList('test'));
    }

    public function testShouldRunWhenHasStringOnToList(): void
    {
        $payload = new DummyPayload(['test' => 'test,test,dummy,']);

        self::assertEquals(['test', 'dummy'], $payload->toList('test'));
    }

    public function testShouldRunWhenHasOtherOnToList(): void
    {
        $payload = new DummyPayload(['test' => 9999]);

        self::assertEquals([9999], $payload->toList('test'));
    }
}
