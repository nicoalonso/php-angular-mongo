<?php

namespace App\Tests\Application\Borrow\Creator;

use App\Application\Borrow\Creator\BorrowCreate;
use App\Application\Borrow\Creator\BorrowCreatePayload;
use App\Domain\Book\Exception\BookNotFoundException;
use App\Domain\Borrow\Exception\BorrowLinesEmptyException;
use App\Domain\Customer\Exception\CustomerNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\CustomerRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowCreateTest extends TestCase
{
    use FixturePayload;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private CustomerRepositoryStub $repoCustomer;
    private BookRepositoryStub $repoBook;
    private BorrowCreate $creator;

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
    }

    public function testShouldFailWhenHasNotLines(): void
    {
        $data = $this->override(lines: [])
            ->getPayload('borrow-create');
        $payload = new BorrowCreatePayload($data);

        $this->expectException(BorrowLinesEmptyException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenBookNotFound(): void
    {
        $data = $this->getPayload('borrow-create');
        $payload = new BorrowCreatePayload($data);

        $this->expectException(BookNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldFailWhenCustomerNotFound(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('borrow-create');
        $payload = new BorrowCreatePayload($data);

        $this->expectException(CustomerNotFoundException::class);
        $this->creator->dispatch($payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoCustomer->put(Ref::CustomerJohnDoe);

        $data = $this->getPayload('borrow-create');
        $payload = new BorrowCreatePayload($data);

        $borrow = $this->creator->dispatch($payload);

        self::assertEquals('John', $borrow->getCustomer()->getName());
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
