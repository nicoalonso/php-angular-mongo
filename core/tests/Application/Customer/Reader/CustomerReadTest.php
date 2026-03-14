<?php

namespace App\Tests\Application\Customer\Reader;

use App\Application\Customer\Reader\CustomerRead;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class CustomerReadTest extends TestCase
{
    private CustomerRepositoryStub $repoCustomer;
    private CustomerRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->reader = new CustomerRead($this->repoCustomer);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(CustomerNotFoundException::class);

        $this->reader->dispatch('unknown-book-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $customer = $this->reader->dispatch('1234567890');

        self::assertEquals('John', $customer->getName());
    }
}
