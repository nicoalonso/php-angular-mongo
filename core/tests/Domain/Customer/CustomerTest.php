<?php

namespace App\Tests\Domain\Customer;

use App\Domain\Common\Address;
use App\Domain\Customer\ContactInfo;
use App\Domain\Customer\Customer;
use App\Domain\Customer\Membership;
use App\Domain\Identity\Exception\NameEmptyException;
use App\Tests\Fixtures\Mothers\AddressMother;
use App\Tests\Fixtures\Mothers\ContactInfoMother;
use App\Tests\Fixtures\Mothers\MembershipMother;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private Membership $membership;
    private ContactInfo $contact;
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membership = MembershipMother::active();
        $this->contact = ContactInfoMother::doe();
        $this->address = AddressMother::anytown();
    }

    public function testShouldFailWhenEmptyName(): void
    {
        $this->expectException(NameEmptyException::class);

        new Customer(
            '',
            '',
            $this->membership,
            $this->contact,
            $this->address,
            '',
            'test',
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $customer = new Customer(
            'John',
            'Doe',
            $this->membership,
            $this->contact,
            $this->address,
            '12345667A',
            'test',
        );

        self::assertEquals('John', $customer->getName());
        self::assertEquals('Doe', $customer->getSurname());
        self::assertEquals($this->membership, $customer->getMembership());
        self::assertEquals($this->contact, $customer->getContact());
        self::assertEquals($this->address, $customer->getAddress());
        self::assertEquals('12345667A', $customer->getVatNumber());
    }

    public function testShouldRunWhenModify(): void
    {
        $customer = new Customer(
            'John',
            'Doe',
            $this->membership,
            $this->contact,
            $this->address,
            '12345667A',
            'test',
        );

        $customer->modify(
            'Jane',
            'Smith',
            $this->contact,
            $this->address,
            '45456456D',
            true,
            'test',
        );

        self::assertEquals('Jane', $customer->getName());
        self::assertEquals('Smith', $customer->getSurname());
        self::assertTrue($customer->getMembership()->isActive());
    }

    public function testShouldRunWhenDeactivate(): void
    {
        $customer = new Customer(
            'John',
            'Doe',
            $this->membership,
            $this->contact,
            $this->address,
            '12345667A',
            'test',
        );

        $customer->modify(
            'Jane',
            'Smith',
            $this->contact,
            $this->address,
            '45456456D',
            false,
            'test',
        );

        self::assertEquals('Jane', $customer->getName());
        self::assertEquals('Smith', $customer->getSurname());
        self::assertFalse($customer->getMembership()->isActive());
    }

    public function testShouldRunWhenGetDescriptor(): void
    {
        $customer = new Customer(
            'John',
            'Doe',
            $this->membership,
            $this->contact,
            $this->address,
            '12345667A',
            'test',
        );
        $descriptor = $customer->getDescriptor();


        self::assertEquals($customer->getId(), $descriptor->getId());
        self::assertEquals('John', $descriptor->getName());
        self::assertEquals('Doe', $descriptor->getSurname());
        self::assertEquals('12345667A', $descriptor->getVatNumber());
        self::assertEquals($this->membership->getNumber(), $descriptor->getNumber());
    }
}
