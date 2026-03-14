<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use App\Domain\Identity\Valet;

final class ListQuery
{
    private const string SORT_PARAM = 'sort';
    private const string PAGE_PARAM = 'page';
    private const string LIMIT_PARAM = 'limit';
    private const array QUERY_RESERVED_KEYS = ['page', 'limit', 'sort'];
    //
    private const string SORT_SEPARATOR_PATTERN = '/[,; ]/';
    private const int FIRST_PAGE = 1;
    private const int LIMIT_DEFAULT = 10;

    private FieldCollection $joins;
    private FilterFieldCollection $filters;
    private SortFieldCollection $sort;
    private int $page;
    private int $limit;

    public function __construct(
        ?FilterFieldCollection $filters = null,
        ?SortFieldCollection $sort = null,
        ?int $page = null,
        ?int $limit = null,
    ) {
        $this->filters = $filters ?? new FilterFieldCollection();
        $this->joins = new FieldCollection();
        $this->sort = $sort ?? new SortFieldCollection();
        $this->page = $page ?? self::FIRST_PAGE;
        $this->limit = $limit ?? self::LIMIT_DEFAULT;
    }

    public static function fromParams(array $query): self
    {
        $params = new Valet($query);
        $page = $params->toInt(self::PAGE_PARAM, null);
        $limit = $params->toInt(self::LIMIT_PARAM, null);
        $sortValue = $params->toString(self::SORT_PARAM);
        $sort = self::parseSortKey($sortValue);
        $filters = self::parseFilters($query);

        return new self($filters, $sort, $page, $limit);
    }

    private static function parseFilters(array $query): FilterFieldCollection
    {
        $filters = new FilterFieldCollection();

        $keyList = array_filter(
            array_keys($query),
            fn($key) => !in_array($key, self::QUERY_RESERVED_KEYS)
        );

        foreach ($keyList as $name) {
            $value = (string) $query[$name];
            list ($name, $interval) = FilterRangeInterval::check($name);

            if ($interval == FilterRangeInterval::NONE) {
                $filter = new FilterField($name, $value);
                $filters->add($filter);
            } else {
                $filter = $filters->findFirst(fn ($key, FilterField $item) => $item->is($name));
                if (null === $filter) {
                    $filter = new FilterField($name, $value, FilterType::RANGE);
                    $filters->add($filter);
                }

                $filter->range($interval, $value);
            }
        }

        return $filters;
    }

    private static function parseSortKey(string $sortValue): SortFieldCollection
    {
        $sort = new SortFieldCollection();
        if (empty($sortValue)) {
            return $sort;
        }

        $list = preg_split(self::SORT_SEPARATOR_PATTERN, $sortValue);
        foreach ($list as $sortItem) {
            if (empty(trim($sortItem))) {
                continue;
            }

            $sort[] = SortField::fromString($sortItem);
        }
        return $sort;
    }

    public function lookup(array $mapping): void
    {
        if (empty($mapping)) {
            return;
        }

        /** @var FilterField $filter */
        foreach ($this->filters as $filter) {
            if (array_key_exists($filter->name(), $mapping)) {
                $filter->lookup($mapping[$filter->name()]);
            }
        }

        /** @var SortField $sortField */
        foreach ($this->sort as $sortField) {
            if (array_key_exists($sortField->name(), $mapping)) {
                $sortField->lookup($mapping[$sortField->name()]);
            }
        }
    }

    public function hasJoins(): bool
    {
        return !$this->joins->isEmpty();
    }

    public function addJoins(FieldCollection $joinList): void
    {
        $this->joins = $joinList;
    }

    public function joins(): FieldCollection
    {
        return $this->joins;
    }

    public function hasFilters(): bool
    {
        return !$this->filters->isEmpty();
    }

    public function addFilter(FilterField $field): void
    {
        $this->filters->add($field);
    }

    public function removeFilter(string $name): void
    {
        $filter = $this->getFilter($name);
        if (null !== $filter) {
            $this->filters->removeElement($filter);
        }
    }

    public function getFilter(string $name): ?FilterField
    {
        return $this->filters->findFirst(fn ($key, FilterField $item) => $item->is($name));
    }

    public function filters(): FilterFieldCollection
    {
        return $this->filters;
    }

    public function hasSort(): bool
    {
        return !$this->sort->isEmpty();
    }

    public function addSort(SortField $field): void
    {
        $this->sort->add($field);
    }

    public function getSort(string $name): ?SortField
    {
        return $this->sort->findFirst(fn ($key, SortField $item) => $item->is($name));
    }

    public function sort(): SortFieldCollection
    {
        return $this->sort;
    }

    public function offset(): int
    {
        return ($this->page - self::FIRST_PAGE) * $this->limit;
    }

    public function page(): ?int
    {
        return $this->page;
    }

    public function limit(): ?int
    {
        return $this->limit;
    }
}
