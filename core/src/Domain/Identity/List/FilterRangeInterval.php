<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

enum FilterRangeInterval: string
{
    case NONE = '';
    case FROM = 'from';
    case TO = 'to';

    /**
     * @return array{0: string, 1: FilterRangeInterval}
     */
    public static function check(string $name): array
    {
        if (str_starts_with($name, self::FROM->value)) {
            $interval = self::FROM;
        } else if (str_starts_with($name, self::TO->value)) {
            $interval = self::TO;
        } else {
            return [$name, self::NONE];
        }

        $name = lcfirst(substr($name, strlen($interval->value)));
        return [$name, $interval];
    }
}