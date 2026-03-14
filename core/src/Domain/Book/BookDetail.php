<?php declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Book\Exception\InvalidIsbnException;
use App\Domain\Book\Exception\InvalidPublishedDateException;
use DateTimeImmutable;

readonly class BookDetail
{
    public function __construct(
        private string $edition,
        private string $isbn,
        private string $language,
        private DateTimeImmutable $publishedAt,
        private int $pages,
    ) {
        $this->check($this->isbn, $this->publishedAt);
    }

    private function check(string $isbn, DateTimeImmutable $publishedAt): void
    {
        if (!preg_match('/^\d{3}-\d{10}$/', $isbn)) {
            throw new InvalidIsbnException();
        }

        $now = new DateTimeImmutable('today midnight');
        $limit = $now->modify('+1 day');
        if ($publishedAt > $limit) {
            throw new InvalidPublishedDateException();
        }
    }

    public function getEdition(): string
    {
        return $this->edition;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getPublishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getPages(): int
    {
        return $this->pages;
    }
}
