<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

use DateTimeImmutable;
use Exception;

enum ValueKind
{
    private const string LIST_SEPARATOR_PATTERN = '/[,; ]/';

    case STRING;
    case BOOLEAN;
    case INTEGER;
    case FLOAT;
    case DATE;

    public function parse(string $value): mixed
    {
        return match ($this) {
            self::STRING => $value,
            self::BOOLEAN => self::toBool($value),
            self::INTEGER => (int) $value,
            self::FLOAT => (float) $value,
            self::DATE => self::toDate($value) ?? '',
        };
    }

    public static function toBool(string $input): bool
    {
        return match (strtolower($input)) {
            'true', '1', 'on', 'yes' => true,
            default => false,
        };
    }

    public static function toDate(string $input): ?DateTimeImmutable
    {
        if (empty($input)) {
            return null;
        }

        try {
            if (is_numeric($input)) {
                $value = new DateTimeImmutable("@$input");
            } else {
                $value = new DateTimeImmutable($input);
            }

        } catch (Exception) {
            $value = null;
        }

        return $value;
    }

    public static function isShortDate(string $value): bool
    {
        return !empty($value)
            && !is_numeric($value)
            && !str_contains($value, 'T');
    }

    /**
     * @return string[]
     */
    public static function toList(string $input): array
    {
        return preg_split(self::LIST_SEPARATOR_PATTERN, $input);
    }
}
