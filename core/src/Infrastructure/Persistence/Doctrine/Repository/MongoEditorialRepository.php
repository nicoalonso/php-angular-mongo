<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-implements MongoRepository<Editorial>
 */
final class MongoEditorialRepository extends MongoRepository implements EditorialRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Editorial::class);
    }

    public function obtainByName(string $name): ?Editorial
    {
        return $this->findOneBy(['name' => $name]);
    }
}
