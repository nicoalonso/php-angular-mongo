<?php

namespace App\Tests\Domain\Book;

use App\Domain\Book\BookDetail;
use App\Domain\Book\Exception\InvalidIsbnException;
use App\Domain\Book\Exception\InvalidPublishedDateException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class BookDetailTest extends TestCase
{
    public function testShouldFailWhenInvalidISBN(): void
    {
        $this->expectException(InvalidIsbnException::class);

        new BookDetail(
            '001',
            'invalid-isbn',
            'English',
            new DateTimeImmutable('2020-01-01'),
            100
        );
    }

    public function testShouldFailWhenInvalidDate(): void
    {
        $this->expectException(InvalidPublishedDateException::class);

        new BookDetail(
            '001',
            '978-1234567890',
            'English',
            new DateTimeImmutable('2500-01-01'),
            100
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $detail = new BookDetail(
            '001',
            '978-1234567890',
            'English',
            new DateTimeImmutable('2020-01-01'),
            100
        );

        self::assertEquals('001', $detail->getEdition());
        self::assertEquals('978-1234567890', $detail->getIsbn());
        self::assertEquals('English', $detail->getLanguage());
        self::assertEquals(new DateTimeImmutable('2020-01-01'), $detail->getPublishedAt());
        self::assertEquals(100, $detail->getPages());
    }
}
