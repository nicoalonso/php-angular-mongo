<?php declare(strict_types=1);

namespace App\Application\Editorial\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Editorial\EditorialRepository;

final class EditorialList extends EntityList
{
    const array EDITORIAL_MAP = [
        'name',
        'comercialName',
        'website' => 'contact.website',
    ];

    public function __construct(EditorialRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::EDITORIAL_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
