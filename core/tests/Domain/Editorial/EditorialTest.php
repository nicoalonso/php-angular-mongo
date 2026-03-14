<?php

namespace App\Tests\Domain\Editorial;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Editorial\Editorial;
use App\Domain\Identity\Exception\NameEmptyException;
use App\Tests\Fixtures\Mothers\AddressMother;
use App\Tests\Fixtures\Mothers\EnterpriseContactMother;
use PHPUnit\Framework\TestCase;

class EditorialTest extends TestCase
{
    private EnterpriseContact $contact;
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = EnterpriseContactMother::anaya();
        $this->address = AddressMother::anytown();
    }

    public function testShouldFailWhenNameEmpty(): void
    {
        $this->expectException(NameEmptyException::class);

        new Editorial(
            '',
            'Comercial Name',
            $this->contact,
            $this->address,
            'test',
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $editorial = new Editorial(
            'Anaya',
            'Anaya Inc.',
            $this->contact,
            $this->address,
            'test',
        );

        $this->assertSame('Anaya', $editorial->getName());
        $this->assertSame('Anaya Inc.', $editorial->getComercialName());
        $this->assertSame($this->contact, $editorial->getContact());
        $this->assertSame($this->address, $editorial->getAddress());
    }

    public function testShouldRunWhenModify(): void
    {
        $editorial = new Editorial(
            'Anaya',
            'Anaya Inc.',
            $this->contact,
            $this->address,
            'test',
        );
        $editorial->modify(
            'Anaya Updated',
            'Anaya Inc. Updated',
            $this->contact,
            $this->address,
            'test',
        );

        $this->assertSame('Anaya Updated', $editorial->getName());
        $this->assertSame('Anaya Inc. Updated', $editorial->getComercialName());
    }

    public function testShouldRunWhenGetDescriptor(): void
    {
        $editorial = new Editorial(
            'Anaya',
            'Anaya Inc.',
            $this->contact,
            $this->address,
            'test',
        );
        $descriptor = $editorial->getDescriptor();

        $this->assertSame($editorial->getId(), $descriptor->getId());
        $this->assertSame($editorial->getName(), $descriptor->getName());
    }
}
