<?php

namespace App\Tests\Application\Sale\Reader;

use App\Application\Sale\Reader\SaleRead;
use App\Domain\Sale\Exception\SaleNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\SaleLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\SaleRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class SaleReadTest extends TestCase
{
    private SaleRepositoryStub $repoSale;
    private SaleLineRepositoryStub $repoSaleLine;
    private SaleRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoSale = new SaleRepositoryStub();
        $this->repoSaleLine = new SaleLineRepositoryStub();
        $this->reader = new SaleRead($this->repoSale, $this->repoSaleLine);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(SaleNotFoundException::class);

        $this->reader->dispatch('unknown-sale-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoSale->put(Ref::SaleJohnDoe1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line1);
        $this->repoSaleLine->attach(Ref::SaleLineJohnDoe1Line2);

        $sale = $this->reader->dispatch('sale-john-doe-1');

        self::assertEquals('John', $sale->getCustomer()->getName());
        self::assertCount(2, $sale->getLines());
    }
}
