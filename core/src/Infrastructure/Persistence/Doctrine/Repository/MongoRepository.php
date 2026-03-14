<?php declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Identity\IdentityRepository;
use App\Domain\Identity\List\FilterField;
use App\Domain\Identity\List\FilterRange;
use App\Domain\Identity\List\FilterType;
use App\Domain\Identity\List\ListQuery;
use App\Domain\Identity\List\ListResult;
use App\Domain\Identity\List\Pagination;
use App\Domain\Identity\List\SortField;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use Doctrine\ODM\MongoDB\Query\Builder as QueryBuilder;
use MongoDB\BSON\Regex;
use Throwable;

/**
 * @psalm-template T
 * @template-implements IdentityRepository<T>
 */
abstract class MongoRepository extends ServiceDocumentRepository implements IdentityRepository
{
    /**
     * @param string $id
     * @return T | null
     * @throws LockException
     * @throws MappingException
     */
    public function obtainById(string $id)
    {
        return $this->find($id);
    }

    /**
     * @param T $entity
     * @throws MongoDBException|Throwable
     */
    public function save($entity): void
    {
        $this->dm->persist($entity);
        $this->dm->flush();
    }

    /**
     * @param T $entity
     * @throws MongoDBException|Throwable
     */
    public function remove($entity): void
    {
        $this->dm->remove($entity);
        $this->dm->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function obtainByQuery(ListQuery $query): ListResult
    {
        $qb = $this->createQueryBuilder();
        $qb->find();
        $this->addFiltersToQueryBuilder($qb, $query);

        $qbCountFilter = clone $qb;
        $countFiltered = $qbCountFilter
            ->count()
            ->getQuery()
            ->execute();

        $this->addSortingToQueryBuilder($qb, $query);
        $items = $qb
            ->skip($query->offset())
            ->limit($query->limit())
            ->getQuery()
            ->execute()
            ->toArray();

        $col = new ArrayCollection($items);
        $pagination = new Pagination($countFiltered, $query->page(), $query->limit());

        return new ListResult($col, $pagination);
    }

    protected function addFiltersToQueryBuilder(QueryBuilder $qb, ListQuery $query): void
    {
        if (!$query->hasFilters()) {
            return;
        }

        /** @var FilterField $filter */
        foreach ($query->filters() as $filter) {
            if (!$filter->hasValue()) {
                continue;
            }

            if ($filter->hasVisitant()) {
                if ($filter->visit($qb)) {
                    continue;
                }
            }

            $field = $qb->field($filter->name());

            match ($filter->type()) {
                FilterType::WILDCARD => $field->equals(new Regex('.*'. $filter->value() .'.*', 'i')),
                FilterType::FUZZY => $field->text($filter->value()),
                FilterType::RANGE => $this->addRangeFilter($field, $filter),
                FilterType::IN => $field->in($filter->value()),
                FilterType::ALL => $field->all($filter->value()),
                FilterType::EXISTS => $field->exists($filter->value()),
                default => $field->equals($filter->value()),
            };
        }
    }

    private function addRangeFilter($field, FilterField $filter): void
    {
        if (!$filter->isRange()) {
            return;
        }

        /** @var FilterRange $range */
        $range = $filter->value();
        if ($range->hasFrom()) {
            $field->gte($range->from());
        }
        if ($range->hasTo()) {
            $field->lte($range->to());
        }
    }

    protected function addSortingToQueryBuilder(QueryBuilder $qb, ListQuery $query): void
    {
        /** @var SortField $sort */
        foreach ($query->sort() as $sort) {
            $qb->sort($sort->name(), $sort->direction());
        }
    }
}
