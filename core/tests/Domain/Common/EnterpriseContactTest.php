<?php

namespace App\Tests\Domain\Common;

use App\Domain\Common\EnterpriseContact;
use PHPUnit\Framework\TestCase;

class EnterpriseContactTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $contact = new EnterpriseContact(
            'info@amazon.com',
            'https://www.amazon.com',
            '+1-800-123-4567',
            '+1-800-987-6543'
        );

        self::assertEquals('info@amazon.com', $contact->getEmail());
        self::assertEquals('https://www.amazon.com', $contact->getWebsite());
        self::assertEquals('+1-800-123-4567', $contact->getPhone1());
        self::assertEquals('+1-800-987-6543', $contact->getPhone2());
    }
}
