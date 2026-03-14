<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Provider\Provider;
use App\Domain\Provider\ProviderRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-extends MongoRepository<Provider>
 */
final class MongoProviderRepository extends MongoRepository implements ProviderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider::class);
    }

    public function obtainByName(string $name): ?Provider
    {
        return $this->findOneBy(['name' => $name]);
    }
}
