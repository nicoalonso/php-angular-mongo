<?php

namespace App\Tests\Domain\Services\BookInspector;

use App\Domain\Services\BookInspector\BookBorrowInspect;
use App\Domain\Services\BookInspector\BookInspectFactory;
use App\Domain\Services\BookInspector\BookSaleInspect;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use PHPUnit\Framework\TestCase;

class BookInspectFactoryTest extends TestCase
{
    private BookInspectFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $repoBorrowLine = new BorrowLineRepositoryStub();
        $this->factory = new BookInspectFactory($repoBorrowLine);
    }

    public function testShouldCreateBookSaleInspect(): void
    {
        $inspector = $this->factory->create(true);

        self::assertInstanceOf(BookSaleInspect::class, $inspector);
    }

    public function testShouldCreateBookBorrowInspect(): void
    {
        $inspector = $this->factory->create(false);

        self::assertInstanceOf(BookBorrowInspect::class, $inspector);
    }
}
