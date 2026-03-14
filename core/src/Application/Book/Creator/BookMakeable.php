<?php declare(strict_types=1);

namespace App\Application\Book\Creator;

use App\Application\Book\Creator\Payload\BookDetailPayload;
use App\Application\Book\Creator\Payload\BookSalePayload;
use App\Domain\Author\Author;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\Book\BookDetail;
use App\Domain\Book\BookSale;
use App\Domain\Book\Exception\InvalidPublishedDateException;
use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\Exception\EditorialNotFoundException;

trait BookMakeable
{
    private function findAuthor(string $authorId): Author
    {
        $author = $this->repoAuthor->obtainById($authorId);
        if (null === $author) {
            throw new AuthorNotFoundException();
        }
        return $author;
    }

    private function findEditorial(string $editorialId): Editorial
    {
        $editorial = $this->repoEditorial->obtainById($editorialId);
        if (null === $editorial) {
            throw new EditorialNotFoundException();
        }
        return $editorial;
    }

    private function makeDetail(BookDetailPayload $payload): BookDetail
    {
        if (null === $payload->getPublishedAt()) {
            throw new InvalidPublishedDateException('Published date is required');
        }

        return new BookDetail(
            $payload->getEdition(),
            $payload->getIsbn(),
            $payload->getLanguage(),
            $payload->getPublishedAt(),
            $payload->getPages(),
        );
    }

    private function makeSale(BookSalePayload $payload): BookSale
    {
        return new BookSale(
            $payload->isSaleable(),
            $payload->getPrice(),
            $payload->getDiscount(),
        );
    }
}
