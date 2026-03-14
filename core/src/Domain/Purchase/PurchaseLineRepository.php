<?php

namespace App\Domain\Purchase;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<PurchaseLine>
 */
interface PurchaseLineRepository extends IdentityRepository
{
    public function obtainByPurchase(string $purchaseId): PurchaseLineCollection;
    public function obtainByBook(string $bookId, ?int $limit = null): PurchaseLineCollection;
}
