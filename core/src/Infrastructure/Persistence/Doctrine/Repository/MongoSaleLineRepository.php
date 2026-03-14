<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Sale\SaleLine;
use App\Domain\Sale\SaleLineCollection;
use App\Domain\Sale\SaleLineRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<SaleLine>
 */
final class MongoSaleLineRepository extends MongoRepository implements SaleLineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleLine::class);
    }

    public function obtainBySale(string $saleId): SaleLineCollection
    {
        $items = $this->findBy(['sale' => $saleId]);
        return new SaleLineCollection($items);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): SaleLineCollection
    {
        $items = $this->findBy(['book.id' => $bookId], limit: $limit);
        return new SaleLineCollection($items);
    }
}
