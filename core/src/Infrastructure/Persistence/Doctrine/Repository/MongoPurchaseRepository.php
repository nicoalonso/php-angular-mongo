<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Purchase\Purchase;
use App\Domain\Purchase\PurchaseCollection;
use App\Domain\Purchase\PurchaseRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<Purchase>
 */
final class MongoPurchaseRepository extends MongoRepository implements PurchaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    public function obtainByProviderAndNumber(string $providerId, string $invoiceNumber): ?Purchase
    {
        return $this->findOneBy([
            'provider.id' => $providerId,
            'invoice.number' => $invoiceNumber,
        ]);
    }

    public function obtainByProvider(string $providerId, ?int $limit = null): PurchaseCollection
    {
        $items = $this->findBy(['provider.id' => $providerId,], limit: $limit);
        return new PurchaseCollection($items);
    }
}
