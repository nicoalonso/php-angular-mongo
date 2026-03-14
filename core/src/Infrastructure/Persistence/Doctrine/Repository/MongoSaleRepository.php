<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Sale\Sale;
use App\Domain\Sale\SaleCollection;
use App\Domain\Sale\SaleRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<Sale>
 */
final class MongoSaleRepository extends MongoRepository implements SaleRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function obtainByNumber(string $number): ?Sale
    {
        return $this->findOneBy(['number' => $number]);
    }

    public function obtainByCustomer(string $customerId, ?int $limit = null): SaleCollection
    {
        $items = $this->findBy(['customer.id' => $customerId], limit: $limit);
        return new SaleCollection($items);
    }
}
