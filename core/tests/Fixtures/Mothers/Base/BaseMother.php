<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers\Base;

use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use Throwable;

abstract class BaseMother
{
    protected static function merge(array $values, array $overrides): array
    {
        $fields = [];
        foreach ($values as $key => $item) {
            list ($value, $mapping, $builder) = self::getMapping($item);
            $value = $overrides[$key] ?? $value;
            $fields[$key] = self::applyMapping($value, $mapping, $builder);
        }

        return $fields;
    }

    /**
     * @return array{ mixed, MotherMapping, ?MotherBuild }
     */
    protected static function getMapping(mixed $item): array
    {
        if (!is_array($item)) {
            return [$item, MotherMapping::NONE, null];
        }

        $map = MotherMapping::NONE;
        $value = null;
        $fqn = null;

        foreach ($item as $element) {
            if ($element instanceof MotherMapping) {
                $map = $element;
            } else if (is_subclass_of($element, BaseMother::class)) {
                $fqn = $element;
            } else {
                $value = $element;
            }
        }

        if (null !== $fqn && null !== $value) {
            $fqn = new MotherBuild($fqn, $value);
            $map = MotherMapping::MOTHER;
            $value = null;
        }

        return [$value, $map, $fqn];
    }

    protected static function applyMapping(mixed $value, MotherMapping $mapping, ?MotherBuild $builder = null): mixed
    {
        try {
            if ($mapping === MotherMapping::REQUIRED && empty($value)) {
                throw new InvalidArgumentException("Field is required");
            }

            if ($mapping === MotherMapping::MOTHER && null !== $builder && null === $value) {
                $value = $builder->build();
            }

            return match ($mapping) {
                MotherMapping::DATE => new DateTime($value),
                MotherMapping::DATE_IMMUTABLE => new DateTimeImmutable($value),
                MotherMapping::REQUIRED,
                MotherMapping::MOTHER,
                MotherMapping::ARRAY,
                MotherMapping::NONE => $value,
            };

        } catch (Throwable $e) {
            trigger_error($e->getMessage());
            return null;
        }
    }
}
