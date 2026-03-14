<?php declare(strict_types=1);

namespace App\Application\Borrow\Sanctioner;

use Psr\Log\LoggerInterface;
use Throwable;

final readonly class BorrowPenaltyDomainHandler
{
    public function __construct(
        private BorrowPenalty   $sanctioner,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(BorrowPenaltyEvent $event): void
    {
        try {
            $penalties = $this->sanctioner->dispatch();
            $this->logger->info('Handled borrow penalty event', [
                'event' => $event->action(),
                'penalties' => $penalties,
            ]);

            // @codeCoverageIgnoreStart
        } catch (Throwable $e) {
            $this->logger->error('Failed to handle borrow penalty event', [
                'event' => $event->action(),
                'error' => $e->getMessage(),
            ]);
        }
        // @codeCoverageIgnoreEnd
    }
}
