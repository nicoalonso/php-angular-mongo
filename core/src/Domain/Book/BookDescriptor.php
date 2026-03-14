<?php declare(strict_types=1);

namespace App\Domain\Book;

readonly class BookDescriptor
{
    public function __construct(
        private string $id,
        private string $title,
        private string $isbn,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }
}
