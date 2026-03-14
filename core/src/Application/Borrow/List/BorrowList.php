<?php declare(strict_types=1);

namespace App\Application\Borrow\List;

use App\Application\Identity\List\EntityList;
use App\Domain\Borrow\BorrowRepository;
use App\Domain\Identity\List\FieldOption;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ValueKind;

final class BorrowList extends EntityList
{
    private const array BORROW_MAP = [
        'customerId' => ['customer.id', FilterType::MATCH],
        'customer' => 'customer.name',
        'customerNumber' => 'customer.number',
        'number',
        'borrowDate' => [FilterType::RANGE, ValueKind::DATE],
        'dueDate' => [FilterType::RANGE, ValueKind::DATE],
        'totalBooks' => [FieldOption::NO_FILTER],
        'returned' => [ FilterType::MATCH, ValueKind::BOOLEAN],
        'penalty' => [ FilterType::MATCH, ValueKind::BOOLEAN],
    ];

    public function __construct(BorrowRepository $repository)
    {
        $fieldMap = self::makeEntityMap(self::BORROW_MAP);
        parent::__construct($repository, $fieldMap);
    }
}
