<?php

namespace App\Tests\Domain\Borrow;

use App\Domain\Borrow\Borrow;
use App\Domain\Borrow\Exception\InvalidBorrowNumberException;
use App\Domain\Customer\Customer;
use App\Tests\Fixtures\Mothers\CustomerMother;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BorrowTest extends TestCase
{
    private Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = CustomerMother::johnDoe();
    }

    public function testShouldFailWhenInvalidNumber(): void
    {
        $this->expectException(InvalidBorrowNumberException::class);

        new Borrow($this->customer, '', 3, 'test');
    }

    public function testShouldRunWhenCreate(): void
    {
        $borrow = new Borrow($this->customer, 'P-00022', 3, 'test');

        self::assertEquals($this->customer->getDescriptor(), $borrow->getCustomer());
        self::assertEquals('P-00022', $borrow->getNumber());
        self::assertEquals(3, $borrow->getTotalBooks());
        self::assertInstanceOf(DateTimeImmutable::class, $borrow->getBorrowDate());
        self::assertInstanceOf(DateTimeImmutable::class, $borrow->getDueDate());
        self::assertFalse($borrow->isReturned());
        self::assertNull($borrow->getReturnedDate());
        self::assertEquals(0, $borrow->getTotalReturnedBooks());
        self::assertFalse($borrow->hasPenalty());
        self::assertEquals(0.0, $borrow->getPenaltyAmount());
    }

    public function testShouldRunWhenModifyPending(): void
    {
        $borrow = new Borrow($this->customer, 'P-00022', 3, 'test');
        $borrow->modify(1, 'test');

        self::assertEquals(1, $borrow->getTotalReturnedBooks());
        self::assertFalse($borrow->isReturned());
        self::assertNull($borrow->getReturnedDate());
    }

    public function testShouldRunWhenModifyComplete(): void
    {
        $borrow = new Borrow($this->customer, 'P-00022', 3, 'test');
        $borrow->modify(3, 'test');

        self::assertEquals(3, $borrow->getTotalReturnedBooks());
        self::assertTrue($borrow->isReturned());
        self::assertNotNull($borrow->getReturnedDate());
    }

    public function testShouldRunWhenPenalize(): void
    {
        $borrow = new Borrow($this->customer, 'P-00022', 3, 'test');
        $borrow->penalize(15.5);

        self::assertTrue($borrow->hasPenalty());
        self::assertEquals(15.5, $borrow->getPenaltyAmount());
    }
}
