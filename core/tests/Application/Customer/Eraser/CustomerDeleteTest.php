<?php

namespace App\Tests\Application\Customer\Eraser;

use App\Application\Customer\Eraser\CustomerAssociatedException;
use App\Application\Customer\Eraser\CustomerDelete;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class CustomerDeleteTest extends TestCase
{
    private CustomerRepositoryStub $repoCustomer;
    private SaleRepositoryStub $repoSale;
    private BorrowRepositoryStub $repoBorrow;
    private CustomerDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->repoSale = new SaleRepositoryStub();
        $this->repoBorrow = new BorrowRepositoryStub();
        $this->eraser = new CustomerDelete(
            $this->repoCustomer,
            $this->repoSale,
            $this->repoBorrow,
        );
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(CustomerNotFoundException::class);
        $this->eraser->dispatch('non-existing-id');
    }

    public function testShouldFailWhenBorrowAssociated(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);
        $this->repoBorrow->attach(Ref::BorrowJohnDoe);

        $this->expectException(CustomerAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldFailWhenSaleAssociated(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);
        $this->repoSale->attach(Ref::SaleJohnDoe1);

        $this->expectException(CustomerAssociatedException::class);
        $this->eraser->dispatch('12345678');
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $this->eraser->dispatch('12345678');

        self::assertNotNull($this->repoCustomer->removed);
    }
}
