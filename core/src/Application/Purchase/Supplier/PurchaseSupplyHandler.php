<?php declare(strict_types=1);

namespace App\Application\Purchase\Supplier;

use App\Application\Purchase\Creator\PurchaseCreatedEvent;
use App\Application\Purchase\Eraser\PurchaseDeletedEvent;
use App\Application\Purchase\Updater\PurchaseUpdatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final readonly class PurchaseSupplyHandler
{
    public function __construct(private PurchaseSupply $supplier) {}

    #[AsMessageHandler]
    public function handleCreated(PurchaseCreatedEvent $event): void
    {
        $this->supplier->dispatch($event->getBooks());
    }

    #[AsMessageHandler]
    public function handleUpdated(PurchaseUpdatedEvent $event): void
    {
        $this->supplier->dispatch($event->getBooks());
    }

    #[AsMessageHandler]
    public function handleDeleted(PurchaseDeletedEvent $event): void
    {
        $this->supplier->dispatch($event->getBooks());
    }
}
