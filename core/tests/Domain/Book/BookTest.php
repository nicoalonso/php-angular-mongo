<?php

namespace App\Tests\Domain\Book;

use App\Domain\Author\Author;
use App\Domain\Book\Book;
use App\Domain\Book\BookDetail;
use App\Domain\Book\BookSale;
use App\Domain\Book\Exception\TitleEmptyException;
use App\Domain\Editorial\Editorial;
use App\Tests\Fixtures\Mothers\AuthorMother;
use App\Tests\Fixtures\Mothers\BookDetailMother;
use App\Tests\Fixtures\Mothers\BookSaleMother;
use App\Tests\Fixtures\Mothers\EditorialMother;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{
    private Author $author;
    private Editorial $editorial;
    private BookDetail $detail;
    private BookSale $sale;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorMother::shakespeare();
        $this->editorial = EditorialMother::anaya();
        $this->detail = BookDetailMother::valid();
        $this->sale = BookSaleMother::valid();
    }

    public function testShouldFailWhenTitleEmpty(): void
    {
        $this->expectException(TitleEmptyException::class);
        new Book(
            '',
            'Description',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $book = new Book(
            'Romeo and Juliet',
            'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );

        self::assertEquals('Romeo and Juliet', $book->getTitle());
        self::assertStringContainsString('Romeo and Juliet', $book->getDescription());
        self::assertEquals($this->author->getDescriptor(), $book->getAuthor());
        self::assertEquals($this->editorial->getDescriptor(), $book->getEditorial());
        self::assertEquals($this->detail, $book->getDetail());
        self::assertEquals($this->sale, $book->getSale());
        self::assertEquals(0, $book->getStock());
    }

    public function testShouldRunWhenModify(): void
    {
        $book = new Book(
            'Romeo and Juliet',
            'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );

        $book->modify(
            'Romeo and Juliet Modified',
            'test',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );

        self::assertEquals('Romeo and Juliet Modified', $book->getTitle());
        self::assertStringContainsString('test', $book->getDescription());
    }

    public function testShouldRunWhenGetDescriptor(): void
    {
        $book = new Book(
            'Romeo and Juliet',
            'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );
        $descriptor = $book->getDescriptor();

        self::assertEquals($book->getId(), $descriptor->getId());
        self::assertEquals($book->getTitle(), $descriptor->getTitle());
        self::assertEquals($book->getDetail()->getIsbn(), $descriptor->getIsbn());
    }

    public function testShouldRunWhenChangeStock(): void
    {
        $book = new Book(
            'Romeo and Juliet',
            'Romeo and Juliet is a tragedy written by William Shakespeare early in his career about two young star-crossed lovers whose deaths ultimately reconcile their feuding families.',
            $this->author,
            $this->editorial,
            $this->detail,
            $this->sale,
            'test',
        );

        $book->changeStock(10);

        self::assertEquals(10, $book->getStock());
    }
}
