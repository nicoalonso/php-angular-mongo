<?php declare(strict_types=1);

namespace App\Application\Provider\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Provider\ProviderRepository;

final class ProviderList extends EntityList
{
    const array PROVIDER_MAP = [
        'name',
        'comercialName',
        'vatNumber',
        'website' => 'contact.website',
    ];

    public function __construct(ProviderRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::PROVIDER_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
