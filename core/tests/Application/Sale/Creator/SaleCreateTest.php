<?php

namespace App\Tests\Application\Sale\Creator;

use App\Application\Sale\Creator\SaleCreate;
use App\Application\Sale\Creator\SaleCreatedEvent;
use App\Application\Sale\Creator\SaleCreatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Domain\Sale\Exception\InvalidSaleDateException;
use App\Domain\Sale\Exception\SaleLinesEmptyException;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class SaleCreateTest extends TestCase
{
    use FixturePayload;

    private SaleRepositoryStub $repoSale;
    private SaleLineRepositoryStub $repoSaleLine;
    private CustomerRepositoryStub $repoCustomer;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private SaleCreate $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->repoSale = new SaleRepositoryStub($this->repoCustomer);
        $this->repoBook = new BookRepositoryStub();
        $this->repoSaleLine = new SaleLineRepositoryStub(
            $this->repoSale,
            $this->repoBook,
        );
        $this->bus = new DomainBusStub();

        $repoSequence = new SequenceNumberRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new SaleCreate(
            $this->repoSale,
            $repoSequence,
            $this->repoSaleLine,
            $this->repoCustomer,
            $this->repoBook,
            $repoUser,
            $this->bus,
        );
    }

    public function testShouldFailWhenInvalidDate(): void
    {
        $data = $this->override(invoice: [])
            ->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        $this->expectException(InvalidSaleDateException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenEmptyLines(): void
    {
        $data = $this->override(lines: [])
            ->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        $this->expectException(SaleLinesEmptyException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenBookNotFound(): void
    {
        $data = $this->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        $this->expectException(BookNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenCustomerNotFound(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $data = $this->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        $this->expectException(CustomerNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $data = $this->getPayload('sale');
        $payload = new SaleCreatePayload($data);

        $sale = $this->creator->dispatch($payload);

        self::assertEquals(121, $sale->getInvoice()->getTotal());
        assertStored($this->repoSale);
        assertStored($this->repoSaleLine);
        assertDispatch($this->bus, SaleCreatedEvent::class);
    }
}
