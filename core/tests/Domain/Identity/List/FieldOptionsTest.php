<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\FieldOption;
use App\Domain\Identity\List\FieldOptions;
use PHPUnit\Framework\TestCase;

class FieldOptionsTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $options = new FieldOptions();

        self::assertTrue($options->canSelect());
        self::assertTrue($options->canFilter());
        self::assertTrue($options->canSort());
        self::assertFalse($options->canExclude());
        self::assertFalse($options->canJoin());
    }

    public function testShouldRunWhenMergeOptions(): void
    {
        $options = new FieldOptions();
        $options->add(FieldOption::NO_SELECT);
        $options->add(FieldOption::NO_SORT);

        self::assertFalse($options->canSelect());
        self::assertTrue($options->canFilter());
        self::assertFalse($options->canSort());
        self::assertFalse($options->canExclude());
    }

    public function testShouldRunWhenMergeNoFilter(): void
    {
        $options = new FieldOptions();
        $options->add(FieldOption::NO_FILTER);

        self::assertTrue($options->canSelect());
        self::assertFalse($options->canFilter());
        self::assertTrue($options->canSort());
        self::assertFalse($options->canExclude());
    }

    public function testShouldRunWhenMergeOptionsWithExclude(): void
    {
        $options = new FieldOptions();
        $options->add(FieldOption::EXCLUDE);

        self::assertTrue($options->canSelect());
        self::assertTrue($options->canFilter());
        self::assertTrue($options->canSort());
        self::assertTrue($options->canExclude());
    }

    public function testShouldRunWhenMergeOptionsWithJoin(): void
    {
        $options = new FieldOptions();
        $options->add(FieldOption::JOIN);

        self::assertTrue($options->canSelect());
        self::assertTrue($options->canFilter());
        self::assertTrue($options->canSort());
        self::assertFalse($options->canExclude());
        self::assertTrue($options->canJoin());
    }
}
