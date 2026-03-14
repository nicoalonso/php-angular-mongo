<?php declare(strict_types=1);

namespace App\Application\Customer\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Customer\CustomerRepository;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;

final class CustomerList extends EntityList
{
    private const array CUSTOMER_MAP = [
        'name',
        'surname',
        'number' => 'membership.number',
        'active' => ['membership.active', FilterType::MATCH, ValueKind::BOOLEAN],
        'city' => 'address.city',
        'vatNumber',
    ];

    public function __construct(CustomerRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::CUSTOMER_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
