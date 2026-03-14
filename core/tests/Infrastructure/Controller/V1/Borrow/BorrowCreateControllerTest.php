<?php

namespace App\Tests\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Creator\BorrowCreate;
use App\Infrastructure\Controller\V1\Borrow\BorrowCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private CustomerRepositoryStub $repoCustomer;
    private BookRepositoryStub $repoBook;
    private BorrowCreate $creator;
    private BorrowCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoCustomer = new CustomerRepositoryStub();
        $this->repoBorrow = new BorrowRepositoryStub($this->repoCustomer);
        $this->repoBook = new BookRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow, $this->repoBook);
        $repoSequence = new SequenceNumberRepositoryStub();
        $repoUser = new UserRepositoryStub();

        $this->creator = new BorrowCreate(
            $this->repoBorrow,
            $repoSequence,
            $this->repoBorrowLine,
            $this->repoCustomer,
            $this->repoBook,
            $repoUser,
        );
        $this->controller = new BorrowCreateController();
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $data = $this->override(lines: [])
            ->getPayload('borrow-create');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $data = $this->getPayload('borrow-create');
        $request = $this->createRequest(request: $data);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
