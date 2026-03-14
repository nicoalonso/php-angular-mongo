<?php

namespace App\Tests\Application\Customer\Creator;

use App\Application\Customer\Creator\CustomerCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class CustomerCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('customer-create');
        $payload = new CustomerCreatePayload($data);

        self::assertEquals('John', $payload->getName());
        self::assertEquals('Doe', $payload->getSurname());
        self::assertEquals('12345667A', $payload->getVatNumber());

        $contact = $payload->getContact();
        self::assertEquals('johndoe@gmail.com', $contact->getEmail());
        self::assertEquals('+1234567890', $contact->getPhone1());
        self::assertEquals('+0987654321', $contact->getPhone2());

        $address = $payload->getAddress();
        self::assertEquals('123 Main Street', $address->getStreet());
        self::assertEquals('12345', $address->getPostalCode());
        self::assertEquals('Anytown', $address->getCity());
        self::assertEquals('Alaska', $address->getProvince());
        self::assertEquals('EEUU', $address->getCountry());
    }
}
