<?php

namespace App\Tests\Infrastructure\Controller\V1\Sale;

use App\Application\Sale\Creator\SaleCreate;
use App\Application\Sale\Creator\SaleCreatedEvent;
use App\Infrastructure\Controller\V1\Sale\SaleCreateController;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class SaleCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private SaleRepositoryStub $repoSale;
    private SaleLineRepositoryStub $repoSaleLine;
    private CustomerRepositoryStub $repoCustomer;
    private BookRepositoryStub $repoBook;
    private DomainBusStub $bus;
    private SaleCreate $creator;
    private SaleCreateController $controller;

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
        $this->controller = new SaleCreateController();
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $data = $this->getPayload('sale');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $data = $this->getPayload('sale');
        $request = $this->createRequest(request: $data);

        $this->controller->__invoke($request, $this->creator);

        assertStored($this->repoSale);
        assertStored($this->repoSaleLine);
        assertDispatch($this->bus, SaleCreatedEvent::class);
    }
}
