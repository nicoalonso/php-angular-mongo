<?php declare(strict_types=1);

namespace App\Presentation\V1\Book;

use App\Domain\Book\Book;
use App\Presentation\Identity\Result;

final class BookListView extends Result
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(Book $book)
    {
        parent::__construct($book);
    }

    /**
     * @param Book $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            'id' => $data->getId(),
            'title' => $data->getTitle(),
            'author' => [
                'id' => $data->getAuthor()->getId(),
                'name' => $data->getAuthor()->getName(),
            ],
            'editorial' => [
                'id' => $data->getEditorial()->getId(),
                'name' => $data->getEditorial()->getName(),
            ],
            'detail' => [
                'edition' => $data->getDetail()->getEdition(),
                'isbn' => $data->getDetail()->getIsbn(),
                'language' => $data->getDetail()->getLanguage(),
                'publishedAt' => $data->getDetail()->getPublishedAt()->format('Y-m-d'),
                'pages' => $data->getDetail()->getPages(),
            ],
            'sale' => [
                'saleable' => $data->getSale()->isSaleable(),
                'price' => $data->getSale()->getPrice(),
                'discount' => $data->getSale()->getDiscount(),
            ],
            'stock' => $data->getStock(),
            'createdBy' => $data->getCreatedBy(),
            'createdAt' => $data->getCreatedAt()->format(DATE_ATOM),
            'updatedBy' => $data->getUpdatedBy(),
            'updatedAt' => $data->getUpdatedAt()?->format(DATE_ATOM),
        ];
    }
}