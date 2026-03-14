<?php declare(strict_types=1);

namespace App\Application\Book\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Book\BookRepository;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;

final class BookList extends EntityList
{
    private const array BOOK_MAP = [
        'title',
        'author' => 'author.name',
        'editorial' => 'editorial.name',
        'isbn' => 'detail.isbn',
        'language' => 'detail.language',
        'publishedAt' => ['detail.publishedAt', FilterType::RANGE, ValueKind::DATE],
        'price' => ['sale.price', FilterType::RANGE, ValueKind::FLOAT],
        'saleable' => ['sale.saleable', FilterType::MATCH, ValueKind::BOOLEAN],
    ];

    public function __construct(BookRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::BOOK_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
