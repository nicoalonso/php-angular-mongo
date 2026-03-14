<?php

namespace App\Tests\Domain\Provider;

use App\Domain\Common\Address;
use App\Domain\Common\EnterpriseContact;
use App\Domain\Identity\Exception\NameEmptyException;
use App\Domain\Provider\Provider;
use App\Tests\Fixtures\Mothers\AddressMother;
use App\Tests\Fixtures\Mothers\EnterpriseContactMother;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    private EnterpriseContact $contact;
    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contact = EnterpriseContactMother::amazon();
        $this->address = AddressMother::anytown();
    }

    public function testShouldFailWhenNameEmpty(): void
    {
        $this->expectException(NameEmptyException::class);
        new Provider('', '', $this->contact, $this->address, '', 'test');
    }

    public function testShouldRunWhenCreate(): void
    {
        $provider = new Provider(
            'Amazon',
            'Amazon, Inc.',
            $this->contact,
            $this->address,
            'B36565656',
            'test',
        );

        self::assertEquals('Amazon', $provider->getName());
        self::assertEquals('Amazon, Inc.', $provider->getComercialName());
        self::assertEquals($this->contact, $provider->getContact());
        self::assertEquals($this->address, $provider->getAddress());
        self::assertEquals('B36565656', $provider->getVatNumber());
    }

    public function testShouldRunWhenModify(): void
    {
        $provider = new Provider(
            'Amazon',
            'Amazon, Inc.',
            $this->contact,
            $this->address,
            'B36565656',
            'test',
        );

        $provider->modify(
            'El Corte Ingles',
            'Corte Ingles, Inc.',
            $this->contact,
            $this->address,
            'B36222222',
            'test',
        );

        self::assertEquals('El Corte Ingles', $provider->getName());
        self::assertEquals('Corte Ingles, Inc.', $provider->getComercialName());
        self::assertEquals($this->contact, $provider->getContact());
        self::assertEquals($this->address, $provider->getAddress());
        self::assertEquals('B36222222', $provider->getVatNumber());
    }

    public function testShouldRunWhenDescriptor(): void
    {
        $provider = new Provider(
            'Amazon',
            'Amazon, Inc.',
            $this->contact,
            $this->address,
            'B36565656',
            'test',
        );
        $descriptor = $provider->getDescriptor();

        self::assertEquals($provider->getId(), $descriptor->getId());
        self::assertEquals($provider->getName(), $descriptor->getName());
    }
}
