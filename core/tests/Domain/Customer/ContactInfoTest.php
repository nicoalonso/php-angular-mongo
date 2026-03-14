<?php

namespace App\Tests\Domain\Customer;

use App\Domain\Customer\ContactInfo;
use PHPUnit\Framework\TestCase;

class ContactInfoTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $contact = new ContactInfo('johndoe@gmail.com', '+1234567890', '+0987654321');

        self::assertEquals('johndoe@gmail.com', $contact->getEmail());
        self::assertEquals('+1234567890', $contact->getPhone1());
        self::assertEquals('+0987654321', $contact->getPhone2());
    }
}
