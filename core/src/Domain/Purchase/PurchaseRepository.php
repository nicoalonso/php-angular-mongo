<?php

namespace App\Domain\Purchase;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<Purchase>
 */
interface PurchaseRepository extends IdentityRepository
{
    public function obtainByProviderAndNumber(string $providerId, string $invoiceNumber): ?Purchase;
    public function obtainByProvider(string $providerId, ?int $limit = null): PurchaseCollection;
}
