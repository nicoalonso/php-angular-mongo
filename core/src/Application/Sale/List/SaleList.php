<?php declare(strict_types=1);

namespace App\Application\Sale\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;
use App\Domain\Sale\SaleRepository;

final class SaleList extends EntityList
{
    private const array SALE_MAP = [
        'customer' => 'customer.name',
        'date' => ['invoice.date', FilterType::RANGE, ValueKind::DATE],
        'number' => 'number',
    ];

    public function __construct(SaleRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::SALE_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
