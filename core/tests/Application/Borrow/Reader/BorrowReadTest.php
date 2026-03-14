<?php

namespace App\Tests\Application\Borrow\Reader;

use App\Application\Borrow\Reader\BorrowRead;
use App\Domain\Borrow\Exception\BorrowNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class BorrowReadTest extends TestCase
{
    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BorrowRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);
        $this->reader = new BorrowRead($this->repoBorrow, $this->repoBorrowLine);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(BorrowNotFoundException::class);

        $this->reader->dispatch('unknown-book-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoBorrow->put(Ref::BorrowJohnDoe);
        $this->repoBorrowLine->attachAll();

        $borrow = $this->reader->dispatch('1234567890');

        self::assertEquals('John', $borrow->getCustomer()->getName());
        self::assertCount(2, $borrow->getLines());
    }
}
