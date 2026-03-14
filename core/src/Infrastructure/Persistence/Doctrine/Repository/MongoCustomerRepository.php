<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @template-implements MongoRepository<Customer>
 */
final class MongoCustomerRepository extends MongoRepository implements CustomerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function obtainByName(string $name, string $surname): ?Customer
    {
        return $this->findOneBy(['name' => $name, 'surname' => $surname]);
    }

    public function obtainByNumber(string $number): ?Customer
    {
        return $this->findOneBy(['membership.number' => $number]);
    }
}
