<?php

namespace App\Tests\Application\Customer\Updater;

use App\Application\Customer\Updater\CustomerUpdate;
use App\Application\Customer\Updater\CustomerUpdatePayload;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class CustomerUpdateTest extends TestCase
{
    use FixturePayload;

    private CustomerRepositoryStub $repoCustomer;
    private CustomerUpdate $updater;
    private CustomerUpdatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new CustomerUpdate(
            $this->repoCustomer,
            $repoUser,
        );

        $data = $this->getPayload('customer-update');
        $this->payload = new CustomerUpdatePayload($data);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(CustomerNotFoundException::class);
        $this->updater->dispatch('non-existing-id', $this->payload);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $customer = $this->updater->dispatch('12345678', $this->payload);

        self::assertSame($this->payload->getName(), $customer->getName());
        assertStored($this->repoCustomer);
    }
}
