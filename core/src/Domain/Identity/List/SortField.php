<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

final class SortField extends Field
{
    public const string ASC_ORDER = 'asc';
    public const string DESC_ORDER = 'desc';
    protected const string ASC_PREFIX = '+';
    protected const string DESC_PREFIX = '-';

    private string $direction;

    public function __construct(string $name, string $direction = self::ASC_ORDER)
    {
        parent::__construct($name);
        $this->direction = $direction;
    }

    public static function fromString(string $sortValue): self
    {
        $direction = self::ASC_ORDER;

        if (str_starts_with($sortValue, self::ASC_PREFIX)) {
            $name = substr($sortValue, 1);
        } else if (str_starts_with($sortValue, self::DESC_PREFIX)) {
            $name = substr($sortValue, 1);
            $direction = self::DESC_ORDER;
        } else {
            $name = $sortValue;
        }

        return new self($name, $direction);
    }

    public function direction(): string
    {
        return $this->direction;
    }
}
