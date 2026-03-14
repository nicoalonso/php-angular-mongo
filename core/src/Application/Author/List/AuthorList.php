<?php declare(strict_types=1);

namespace App\Application\Author\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Author\AuthorRepository;

final class AuthorList extends EntityList
{
    private const array AUTHOR_MAP = [
        'name',
        'realName',
        'nationality',
    ];

    public function __construct(AuthorRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::AUTHOR_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
