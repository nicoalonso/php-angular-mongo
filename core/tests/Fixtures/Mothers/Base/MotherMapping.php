<?php

namespace App\Tests\Fixtures\Mothers\Base;

enum MotherMapping
{
    case NONE;
    case ARRAY;
    case DATE;
    case DATE_IMMUTABLE;
    case REQUIRED;
    case MOTHER;
}
