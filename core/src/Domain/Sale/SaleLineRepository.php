<?php

namespace App\Domain\Sale;

use App\Domain\Identity\IdentityRepository;

/**
 * @template-extends IdentityRepository<SaleLine>
 */
interface SaleLineRepository extends IdentityRepository
{
    public function obtainBySale(string $saleId): SaleLineCollection;
    public function obtainByBook(string $bookId, ?int $limit = null): SaleLineCollection;
}
