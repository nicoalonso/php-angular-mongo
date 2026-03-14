<?php declare(strict_types=1);

namespace App\Application\Book\Creator\Payload;

use App\Domain\Identity\Payload;
use DateTimeImmutable;

final class BookDetailPayload extends Payload
{
    private string $edition;
    private string $isbn;
    private string $language;
    private ?DateTimeImmutable $publishedAt;
    private int $pages;

    public function __construct(array $payload)
    {
        parent::__construct($payload);

        $this->edition = $this->data->toString('edition');
        $this->isbn = $this->data->toString('isbn');
        $this->language = $this->data->toString('language');
        $this->publishedAt = $this->data->toDateImmutable('publishedAt', DATE_SHORT);
        $this->pages = $this->data->toInt('pages');
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

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function getPages(): int
    {
        return $this->pages;
    }
}
