<?php

namespace App\Tests\Domain\Author;

use App\Domain\Author\Author;
use App\Domain\Author\Exception\InvalidBirthDateException;
use App\Domain\Author\Exception\InvalidDeathDateException;
use App\Domain\Identity\Exception\NameEmptyException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    public function testShouldFailWhenNameIsEmpty(): void
    {
        $this->expectException(NameEmptyException::class);

        new Author(
            '',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1564-04-23'),
            new DateTimeImmutable('1616-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
    }

    public function testShouldFailWhenInvalidBirthDate(): void
    {
        $this->expectException(InvalidBirthDateException::class);

        new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('2500-04-23'),
            new DateTimeImmutable('1616-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
    }

    public function testShouldFailWhenInvalidDeathDate(): void
    {
        $this->expectException(InvalidDeathDateException::class);

        new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1564-04-23'),
            new DateTimeImmutable('2500-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
    }

    public function testShouldFailWhenInvalidDeathDateBeforeBirthDate(): void
    {
        $this->expectException(InvalidDeathDateException::class);
        $this->expectExceptionMessage('Death date cannot be before birth date');

        new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1616-04-23'),
            new DateTimeImmutable('1564-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
    }

    public function testShouldRunWhenCreate(): void
    {
        $author = new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1564-04-23'),
            new DateTimeImmutable('1616-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );

        self::assertNotEmpty($author->getId());
        self::assertEquals('William Shakespeare', $author->getName());
        self::assertEquals('William Shakespeare', $author->getRealName());
        self::assertEquals('Tragedy, Comedy, History', $author->getGenres());
        self::assertEquals('William Shakespeare was an English playwright, poet, and actor.', $author->getBiography());
        self::assertEquals('English', $author->getNationality());
        self::assertEquals(new DateTimeImmutable('1564-04-23'), $author->getBirthDate());
        self::assertEquals(new DateTimeImmutable('1616-04-23'), $author->getDeathDate());
        self::assertEquals('https://example.com/shakespeare.jpg', $author->getPhotoUrl());
        self::assertEquals('https://en.wikipedia.org/wiki/William_Shakespeare', $author->getWebsite());
    }

    public function testShouldRunWhenGetDescriptor(): void
    {
        $author = new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1564-04-23'),
            new DateTimeImmutable('1616-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
        $descriptor = $author->getDescriptor();

        self::assertEquals($author->getId(), $descriptor->getId());
        self::assertEquals($author->getName(), $descriptor->getName());
    }

    public function testShouldRunWhenModify(): void
    {
        $author = new Author(
            'William Shakespeare',
            'William Shakespeare',
            'Tragedy, Comedy, History',
            'William Shakespeare was an English playwright, poet, and actor.',
            'English',
            new DateTimeImmutable('1564-04-23'),
            new DateTimeImmutable('1616-04-23'),
            'https://example.com/shakespeare.jpg',
            'https://en.wikipedia.org/wiki/William_Shakespeare',
            'test'
        );
        $author->modify(
            'Miguel de Cervantes',
            'Miguel de Cervantes Saavedra',
            'Novel, Drama, Poetry',
            'Miguel de Cervantes was a Spanish writer widely regarded as one of the greatest writers in the Spanish language.',
            'Spanish',
            new DateTimeImmutable('1547-09-29'),
            new DateTimeImmutable('1616-04-22'),
            'https://example.com/cervantes.jpg',
            'https://en.wikipedia.org/wiki/Miguel_de_Cervantes',
            'test'
        );

        self::assertEquals('Miguel de Cervantes', $author->getName());
        self::assertEquals('Miguel de Cervantes Saavedra', $author->getRealName());
        self::assertEquals('Novel, Drama, Poetry', $author->getGenres());
        self::assertEquals('Miguel de Cervantes was a Spanish writer widely regarded as one of the greatest writers in the Spanish language.', $author->getBiography());
        self::assertEquals('Spanish', $author->getNationality());
        self::assertEquals(new DateTimeImmutable('1547-09-29'), $author->getBirthDate());
        self::assertEquals(new DateTimeImmutable('1616-04-22'), $author->getDeathDate());
        self::assertEquals('https://example.com/cervantes.jpg', $author->getPhotoUrl());
        self::assertEquals('https://en.wikipedia.org/wiki/Miguel_de_Cervantes', $author->getWebsite());
    }
}
