<?php declare(strict_types=1);

namespace App\Application\Purchase\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;
use App\Domain\Purchase\PurchaseRepository;

final class PurchaseList extends EntityList
{
    private const array PURCHASE_MAP = [
        'provider' => 'provider.name',
        'purchasedAt' => [FilterType::RANGE, ValueKind::DATE],
        'invoiceNumber' => 'invoice.number',
    ];

    public function __construct(PurchaseRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::PURCHASE_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
