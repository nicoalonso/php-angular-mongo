<?php declare(strict_types=1);

namespace App\Domain\Identity\List;

enum FieldOption
{
    case NO_SELECT;
    case NO_FILTER;
    case NO_SORT;
    case EXCLUDE;
    case JOIN;
}
