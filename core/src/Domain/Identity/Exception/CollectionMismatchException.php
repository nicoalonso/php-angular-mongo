<?php declare(strict_types=1);

namespace App\Domain\Identity\Exception;

use DateTimeInterface;

final class CollectionMismatchException extends BadRequestException
{
    private const string MESSAGE_FORMAT = 'Value must be of type %s; value is %s';

    public function __construct(string $type, mixed $value)
    {
        $valueString = $this->toolValueToString($value);
        $message = sprintf(self::MESSAGE_FORMAT, $type, $valueString);
        parent::__construct($message);
    }

    protected function toolValueToString($value): string
    {
        // null
        if ($value === null) {
            return 'NULL';
        }

        // array
        if (is_array($value)) {
            return 'Array';
        }

        // boolean constants
        if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        // scalar types (integer, float, string)
        if (is_scalar($value)) {
            return (string) $value;
        }

        // resource
        if (is_resource($value)) {
            return '(' . get_resource_type($value) . ' resource #' . (int) $value . ')';
        }

        // If we don't know what it is, use var_export().
        // @codeCoverageIgnoreStart
        if (!is_object($value)) {
            return '(' . var_export($value, true) . ')';
        }
        // @codeCoverageIgnoreEnd

        // From here, $value should be an object.

        // object of type \DateTime
        if ($value instanceof DateTimeInterface) {
            return '('. $value->format('c') . ' DateTime)';
        }

        // unknown type
        return '(' . get_class($value) . ' Object)';
    }
}
