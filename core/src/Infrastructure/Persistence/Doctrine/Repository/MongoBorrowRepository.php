<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\BorrowCollection;
use App\Domain\Borrow\BorrowRepository;
use DateTime;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<Borrow>
 */
final class MongoBorrowRepository extends MongoRepository implements BorrowRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrow::class);
    }

    public function obtainByNumber(string $number): ?Borrow
    {
        return $this->findOneBy(['number' => $number]);
    }

    public function obtainByCustomer(string $customerId, ?int $limit = null): BorrowCollection
    {
        $items = $this->findBy(['customer.id' => $customerId], limit: $limit);
        return new BorrowCollection($items);
    }

    public function obtainByOverdue(): BorrowCollection
    {
        $items = $this->createQueryBuilder()
            ->field('returned')->equals(false)
            ->field('dueDate')->lt(new DateTime())
            ->getQuery()
            ->execute()
            ->toArray();

        return new BorrowCollection($items);
    }
}
