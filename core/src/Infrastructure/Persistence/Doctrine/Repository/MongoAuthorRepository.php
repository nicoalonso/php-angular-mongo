<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<Author>
 */
final class MongoAuthorRepository extends MongoRepository implements AuthorRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function obtainByName(string $name): ?Author
    {
        return $this->findOneBy(['name' => $name]);
    }
}
