<?php

namespace App\Tests\Application\Book\Creator;

use App\Application\Book\Creator\BookCreatePayload;
use App\Tests\Fixtures\FixturePayload;
use PHPUnit\Framework\TestCase;

class BookCreatePayloadTest extends TestCase
{
    use FixturePayload;

    public function testShouldRunWhenCreate(): void
    {
        $data = $this->getPayload('book-create');
        $payload = new BookCreatePayload($data);

        self::assertEquals('Romeo and Juliet', $payload->getTitle());
        self::assertStringContainsString('Romeo and Juliet', $payload->getDescription());
        self::assertEquals("d6e20876-6f65-5181-a656-b34086d68b4f", $payload->getAuthorId());
        self::assertEquals("d3df0364-5020-5525-b2d3-d779a1c426d6", $payload->getEditorialId());

        $detail = $payload->getDetail();
        self::assertEquals("First Edition", $detail->getEdition());
        self::assertEquals("English", $detail->getLanguage());
        self::assertEquals("2024-01-01", $detail->getPublishedAt()->format(DATE_SHORT));
        self::assertEquals('978-1234567890', $detail->getIsbn());
        self::assertEquals(200, $detail->getPages());

        $sale = $payload->getSale();
        self::assertTrue($sale->isSaleable());
        self::assertEquals(19.99, $sale->getPrice());
        self::assertEquals(0.0, $sale->getDiscount());
    }
}
