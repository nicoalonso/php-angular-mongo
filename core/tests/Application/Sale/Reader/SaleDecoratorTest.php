<?php

namespace App\Tests\Application\Sale\Reader;

use App\Application\Sale\Reader\SaleDecorator;
use App\Domain\Sale\SaleLineCollection;
use App\Tests\Fixtures\Mothers\SaleLineMother;
use App\Tests\Fixtures\Mothers\SaleMother;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class SaleDecoratorTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $sale = SaleMother::johnDoeSale1();
        $lines = new SaleLineCollection([
            SaleLineMother::johnSale1Line1(),
            SaleLineMother::johnSale1Line2(),
        ]);

        $decorator = new SaleDecorator($sale, $lines);

        self::assertEquals($sale, $decorator->getSale());
        self::assertCount(2, $decorator->getLines());
        self::assertEquals($sale->getId(), $decorator->getId());
    }

    public function testShouldFailWhenBadMethod(): void
    {
        $sale = SaleMother::johnDoeSale1();
        $lines = new SaleLineCollection();

        $decorator = new SaleDecorator($sale, $lines);

        $this->expectException(BadMethodCallException::class);
        $decorator->unknownMethod();
    }
}
