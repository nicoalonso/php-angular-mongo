<?php declare(strict_types=1);

namespace App\Application\Sale\Reader;

use App\Domain\Sale\Exception\SaleNotFoundException;
use App\Domain\Sale\SaleLineRepository;
use App\Domain\Sale\SaleRepository;

final readonly class SaleRead
{
    public function __construct(
        private SaleRepository $repoSale,
        private SaleLineRepository $repoSaleLine,
    ) {}

    public function dispatch(string $saleId): SaleDecorator
    {
        $sale = $this->repoSale->obtainById($saleId);
        if (null === $sale) {
            throw new SaleNotFoundException();
        }

        $lines = $this->repoSaleLine->obtainBySale($saleId);

        return new SaleDecorator($sale, $lines);
    }
}
