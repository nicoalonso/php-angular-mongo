<?php

namespace App\Tests\Domain\Bus;

use App\Domain\Bus\DomainEvent;
use App\Domain\Bus\DomainRoute;
use PHPUnit\Framework\TestCase;

class DummyDomainEvent extends DomainEvent
{
    public function __construct()
    {
        parent::__construct('dummy.action', 'dummy.type', DomainRoute::ALL);
    }
}

class DomainEventTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $event = new DummyDomainEvent();

        $this->assertSame('dummy.action', $event->action());
        $this->assertSame('dummy.type', $event->type());
        $this->assertSame(DomainRoute::ALL, $event->route());
    }
}
