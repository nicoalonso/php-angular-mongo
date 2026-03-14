<?php

namespace App\Tests\Application\Bus\Failed;

use App\Application\Bus\Failed\FailedDomainEvent;
use App\Application\Bus\Failed\FailedDomainHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class FailedDomainHandlerTest extends TestCase
{
    public function testShouldRunWhenHandle(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(self::once())
            ->method('error')
            ->with(
                'Failed, Wrong Event!',
                [
                    'action' => 'test_action',
                    'body' => ['key' => 'value'],
                ]
            );
        $handler = new FailedDomainHandler($logger);

        $event = new FailedDomainEvent('test_action', ['key' => 'value']);
        $handler->__invoke($event);
    }
}
