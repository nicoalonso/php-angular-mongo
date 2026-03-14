<?php declare(strict_types=1);

namespace App\Application\Purchase\Reader;

use App\Domain\Purchase\Exception\PurchaseNotFoundException;
use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseLineRepository;
use App\Domain\Purchase\PurchaseRepository;

final readonly class PurchaseRead
{
    public function __construct(
        private PurchaseRepository $repoPurchase,
        private PurchaseLineRepository $repoPurchaseLine,
    ) {}

    public function dispatch(string $purchaseId): PurchaseDecorator
    {
        $purchase = $this->repoPurchase->obtainById($purchaseId);
        if (null === $purchase) {
            throw new PurchaseNotFoundException();
        }

        $lines = $this->repoPurchaseLine->obtainByPurchase($purchaseId);

        return new PurchaseDecorator($purchase, $lines);
    }
}
