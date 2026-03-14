<?php declare(strict_types=1);

namespace App\Application\Identity\List;

use App\Domain\Identity\IdentityRepository;
use App\Domain\Identity\List\Exception\InvalidFilterException;
use App\Domain\Identity\List\Exception\InvalidSortFieldException;
use App\Domain\Identity\List\FieldMapList;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ListQuery;
use App\Domain\Identity\List\ListResult;
use App\Domain\Identity\List\SortField;
use App\Domain\Identity\List\ValueKind;

abstract class EntityList
{
    protected const array ENTITY_MAP = [
        'id',
        'createdBy',
        'createdAt' => [FilterType::RANGE, ValueKind::DATE],
        'updatedBy',
        'updatedAt' => [FilterType::RANGE, ValueKind::DATE],
    ];

    protected FieldMapList $fieldMap;
    protected IdentityRepository $repository;

    public function __construct(IdentityRepository $repository, array $fieldMap = [])
    {
        $this->repository = $repository;
        $this->fieldMap = new FieldMapList($fieldMap);
    }

    public static function makeEntityMap(array $fieldMap): array
    {
        return array_merge(self::ENTITY_MAP, $fieldMap);
    }

    public function dispatch(ListQuery $query): ListResult
    {
        $this->checkQuery($query);
        $this->addJoins($query);
        $this->handleFilters($query);

        return $this->repository->obtainByQuery($query);
    }

    protected function checkQuery(ListQuery $query): void
    {
        $this->isValidFilterOrFail($query);
        $this->isValidSortFieldOrFail($query);
    }

    protected function isValidFilterOrFail(ListQuery $query): void
    {
        if (!$query->hasFilters()) {
            return;
        }

        foreach ($query->filters() as $filter) {
            if (!$this->fieldMap->canFilter($filter)) {
                throw new InvalidFilterException($filter->alias());
            }
        }
    }

    protected function isValidSortFieldOrFail(ListQuery $query): void
    {
        if (!$query->hasSort()) {
            return;
        }

        foreach ($query->sort() as $sortField) {
            if (!$this->fieldMap->canSort($sortField)) {
                throw new InvalidSortFieldException($sortField->alias());
            }
        }
    }

    protected function addJoins(ListQuery $query): void
    {
        $joinList = $this->fieldMap->getJoins();
        $query->addJoins($joinList);
    }

    protected function handleFilters(ListQuery $query): void {
        if (!$query->hasSort() && $this->fieldMap->hasField('createdAt')) {
            // Add default sort by createdAt desc
            $dateSort = new SortField('createdAt', 'desc');
            $query->addSort($dateSort);
        }
    }
}
