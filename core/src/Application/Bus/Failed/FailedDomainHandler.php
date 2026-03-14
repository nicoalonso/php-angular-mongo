<?php declare(strict_types=1);

namespace App\Application\Bus\Failed;

use Psr\Log\LoggerInterface;

final readonly class FailedDomainHandler
{
    public function __construct(private LoggerInterface $logger) {}

    public function __invoke(FailedDomainEvent $event): void
    {
        $this->logger->error('Failed, Wrong Event!', [
            'action' => $event->action(),
            'body' => $event->body(),
        ]);
    }
}