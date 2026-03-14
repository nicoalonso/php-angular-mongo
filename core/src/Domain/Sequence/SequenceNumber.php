<?php declare(strict_types=1);

namespace App\Domain\Sequence;

use App\Domain\Identity\Identity;

class SequenceNumber extends Identity
{
    private const string NUMBER_FORMAT = '%s%05d';

    private SequenceType $type;
    private string $prefix;
    private int $number;

    public function __construct(SequenceType $type)
    {
        parent::__construct();

        $this->type = $type;
        $this->prefix = $type->getPrefix();
        $this->number = 1;
    }

    public function __toString(): string
    {
        return $this->format();
    }

    public function format(): string
    {
        return sprintf(self::NUMBER_FORMAT, $this->prefix, $this->number);
    }

    public function next(): self
    {
        $this->number++;
        return $this;
    }

    public function getType(): SequenceType
    {
        return $this->type;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
