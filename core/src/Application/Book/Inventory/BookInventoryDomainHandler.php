<?php declare(strict_types=1);

namespace App\Application\Book\Inventory;

use Psr\Log\LoggerInterface;
use Throwable;

final readonly class BookInventoryDomainHandler
{
    public function __construct(
        private BookInventory $inventory,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(BookInventoryEvent $event): void
    {
        try {
            $this->inventory->dispatch($event->getDescriptor());

        } catch (Throwable $e) {
            $message = sprintf(
                'Error while dispatching book inventory event for descriptor %s: %s',
                $event->getDescriptor()->getId(),
                $e->getMessage()
            );
            $this->logger->error($message);
        }
    }
}
