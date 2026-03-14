<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Customer\Customer;
use App\Domain\Customer\CustomerRepository;
use App\Tests\Fixtures\Mothers\CustomerMother;
use App\Tests\Fixtures\Ref;

/**
 * @template-extends EntityRepositoryStub<Customer>
 */
final class CustomerRepositoryStub extends EntityRepositoryStub implements CustomerRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtainByName(string $name, string $surname): ?Customer
    {
        return $this->read;
    }

    public function obtainByNumber(string $number): ?Customer
    {
        return $this->read;
    }

    protected function makeFixtures(): void
    {
        $johnDoe = CustomerMother::johnDoe();
        $this->addFixture(Ref::CustomerJohnDoe, $johnDoe);
    }
}
