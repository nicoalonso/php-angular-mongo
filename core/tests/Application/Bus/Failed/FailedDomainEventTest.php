<?php

namespace App\Tests\Application\Bus\Failed;

use App\Application\Bus\Failed\FailedDomainEvent;
use PHPUnit\Framework\TestCase;

class FailedDomainEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $event = new FailedDomainEvent('test_action', ['key' => 'value']);

        self::assertSame('test_action', $event->action());
        self::assertSame(['key' => 'value'], $event->body());
    }
}
