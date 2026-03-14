<?php declare(strict_types=1);

namespace App\Application\Purchase\Eraser;

use App\Domain\Bus\DomainBus;
use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use App\Domain\Purchase\PurchaseLine;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Purchase\PurchaseRepository;

final readonly class PurchaseDelete
{
    public function __construct(
        private PurchaseRepository     $repoPurchase,
        private PurchaseLineRepository $repoPurchaseLine,
        private DomainBus              $bus,
    ) {}

    public function dispatch(string $purchaseId): void
    {
        $purchase = $this->repoPurchase->obtainById($purchaseId);
        if (null === $purchase) {
            throw new PurchaseNotFoundException();
        }

        $lines = $this->repoPurchaseLine->obtainByPurchase($purchaseId);

        $this->repoPurchase->remove($purchase);
        foreach ($lines as $line) {
            $this->repoPurchaseLine->remove($line);
        }

        $books = $lines->map(fn (PurchaseLine $line) => $line->getBook())
            ->toArray();

        $event = new PurchaseDeletedEvent($purchase, $books);
        $this->bus->dispatch($event);
    }
}
