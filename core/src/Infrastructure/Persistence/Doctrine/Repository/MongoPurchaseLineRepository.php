<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Purchase\PurchaseLine;
use App\Domain\Purchase\PurchaseLineCollection;
use App\Domain\Purchase\PurchaseLineRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<PurchaseLine>
 */
final class MongoPurchaseLineRepository extends MongoRepository implements PurchaseLineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchaseLine::class);
    }

    public function obtainByPurchase(string $purchaseId): PurchaseLineCollection
    {
        $items = $this->findBy(['purchase' => $purchaseId]);
        return new PurchaseLineCollection($items);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): PurchaseLineCollection
    {
        $items = $this->findBy(['book.id' => $bookId], limit: $limit);
        return new PurchaseLineCollection($items);
    }
}
