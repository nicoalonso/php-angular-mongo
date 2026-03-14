<?php

namespace App\Tests\Application\Borrow\Reader;

use App\Application\Borrow\Reader\BorrowDecorator;
use App\Domain\Borrow\BorrowLineCollection;
use App\Tests\Fixtures\Mothers\BorrowLineMother;
use App\Tests\Fixtures\Mothers\BorrowMother;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class BorrowDecoratorTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $borrow = BorrowMother::johnDoe();
        $lines = new BorrowLineCollection([
            BorrowLineMother::romeoAndJuliet(),
            BorrowLineMother::donQuijote(),
        ]);

        $decorator = new BorrowDecorator($borrow, $lines);

        self::assertEquals($borrow, $decorator->getBorrow());
        self::assertCount(2, $decorator->getLines());
        self::assertEquals($borrow->getId(), $decorator->getId());
    }

    public function testShouldFailWhenBadMethod(): void
    {
        $borrow = BorrowMother::johnDoe();
        $lines = new BorrowLineCollection();

        $decorator = new BorrowDecorator($borrow, $lines);

        $this->expectException(BadMethodCallException::class);
        $decorator->unknownMethod();
    }
}
