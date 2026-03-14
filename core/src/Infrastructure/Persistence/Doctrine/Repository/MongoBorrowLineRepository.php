<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Borrow\BorrowLine;
use App\Domain\Borrow\BorrowLineCollection;
use App\Domain\Borrow\BorrowLineRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<BorrowLine>
 */
final class MongoBorrowLineRepository extends MongoRepository implements BorrowLineRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BorrowLine::class);
    }

    public function obtainByBorrow(string $borrowId): BorrowLineCollection
    {
        $items = $this->findBy(['borrow' => $borrowId]);
        return new BorrowLineCollection($items);
    }

    public function obtainByBook(string $bookId, ?int $limit = null): BorrowLineCollection
    {
        $items = $this->findBy(['book.id' => $bookId], limit: $limit);
        return new BorrowLineCollection($items);
    }

    public function obtainActiveByBook(string $bookId): BorrowLineCollection
    {
        $items = $this->findBy(['book.id' => $bookId, 'returned' => false]);
        return new BorrowLineCollection($items);
    }
}
