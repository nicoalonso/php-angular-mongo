<?php declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Identity\Exception\CollectionMismatchException;
use Closure;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * A Collection of defined types
 *
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @template-implements ArrayCollection<TKey,T>
 * @psalm-consistent-constructor
 */
abstract class AbstractCollection extends ArrayCollection
{
    use CaseChangeable;

    public const string SORT_ASC = 'asc';
    public const string SORT_DESC = 'desc';

    public function __construct(array|Collection $elements = [])
    {
        if ($elements instanceof Collection) {
            $elements = $elements->toArray();
        }

        $this->checkList($elements);
        parent::__construct($elements);
    }

    /**
     * @param Collection ...$collections
     */
    public function extend(Collection ...$collections): void
    {
        foreach ($collections as $collection) {
            foreach ($collection as $key => $item) {
                if (is_string($key)) {
                    $this->set($key, $item);
                } else {
                    $this->add($item);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function add($element): bool
    {
        $this->checkType($element);
        parent::add($element);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value): void
    {
        $this->checkType($value);
        parent::set($key, $value);
    }

    protected function checkList(array $elements = []): void
    {
        foreach ($elements as $value) {
            $this->checkType($value);
        }
    }

    protected function checkType(mixed $value): void
    {
        $type = $this->getType();
        // @codeCoverageIgnoreStart
        $isValid = match ($type) {
            'array' => is_array($value),
            'bool', 'boolean' => is_bool($value),
            'callable' => is_callable($value),
            'float', 'double' => is_float($value),
            'int', 'integer' => is_int($value),
            'null' => $value === null,
            'numeric' => is_numeric($value),
            'object' => is_object($value),
            'resource' => is_resource($value),
            'scalar' => is_scalar($value),
            'string' => is_string($value),
            'mixed' => true,
            default => $value instanceof $type,
        };
        // @codeCoverageIgnoreEnd

        if (!$isValid) {
            throw new CollectionMismatchException($type, $value);
        }
    }

    public function map(Closure $func): ArrayCollection
    {
        return new ArrayCollection(array_map($func, $this->toArray()));
    }

    public function sort(string $field, string $order = self:: SORT_ASC): static
    {
        $nOrder = strcasecmp($order, self::SORT_DESC) === 0 ? -1 : 1;
        $data = $this->toArray();

        usort($data, function ($a, $b) use ($field, $nOrder) {
            $aValue = $this->extractValue($a, $field);
            $bValue = $this->extractValue($b, $field);
            return ($aValue <=> $bValue) * $nOrder;
        });

        return $this->createFrom($data);
    }

    public function usort(Closure $sortFnc): static
    {
        $data = $this->toArray();
        usort($data, $sortFnc);
        return $this->createFrom($data);
    }

    public abstract function getType(): string;
}
