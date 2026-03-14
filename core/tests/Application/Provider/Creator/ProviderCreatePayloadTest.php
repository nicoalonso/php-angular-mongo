<?php

namespace App\Tests\Application\Provider\Creator;

use App\Application\Provider\Creator\ProviderCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class ProviderCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('provider');
        $payload = new ProviderCreatePayload($data);

        $this->assertEquals("Amazon", $payload->getName());
        $this->assertEquals("Amazon, Inc.", $payload->getComercialName());
        $this->assertEquals("B36565656", $payload->getVatNumber());

        $contact = $payload->getContact();
        $this->assertEquals("info@amazon.com", $contact->getEmail());
        $this->assertEquals("https://www.amazon.com", $contact->getWebsite());
        $this->assertEquals("+1-800-123-4567", $contact->getPhone1());
        $this->assertEquals("+1-800-987-6543", $contact->getPhone2());

        $address = $payload->getAddress();
        $this->assertEquals("123 Main Street", $address->getStreet());
        $this->assertEquals("12345", $address->getPostalCode());
        $this->assertEquals("Anytown", $address->getCity());
        $this->assertEquals("Alaska", $address->getProvince());
        $this->assertEquals("EEUU", $address->getCountry());
    }
}
