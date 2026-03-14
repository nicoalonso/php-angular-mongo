<?php

namespace App\Tests\Application\Customer\Creator;

use App\Application\Customer\Creator\CustomerCreate;
use App\Application\Customer\Creator\CustomerCreatePayload;
use App\Domain\Customer\Exception\CustomerAlreadyExistException;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class CustomerCreateTest extends TestCase
{
    use FixturePayload;

    private CustomerRepositoryStub $repoCustomer;
    private CustomerCreate $creator;
    private CustomerCreatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $repoSequence = new SequenceNumberRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new CustomerCreate(
            $this->repoCustomer,
            $repoSequence,
            $repoUser,
        );

        $data = $this->getPayload('customer-create');
        $this->payload = new CustomerCreatePayload($data);
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $this->expectException(CustomerAlreadyExistException::class);
        $this->creator->dispatch($this->payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $customer = $this->creator->dispatch($this->payload);

        self::assertEquals('John', $customer->getName());
        self::assertEquals('SN00002', $customer->getMembership()->getNumber());
        assertStored($this->repoCustomer);
    }
}
