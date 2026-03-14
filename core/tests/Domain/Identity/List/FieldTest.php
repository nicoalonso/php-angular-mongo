<?php

namespace App\Tests\Domain\Identity\List;

use App\Domain\Identity\List\Field;
use App\Domain\Identity\List\FieldMap;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $field = new Field('name');

        self::assertEquals('name', $field->alias());
        self::assertEquals('name', $field->name());
        self::assertTrue($field->is('name'));
    }

    public function testShouldRunWhenCreateFromMap(): void
    {
        $fieldMap = new FieldMap('_id', 'id');
        $field = Field::fromMap($fieldMap);

        self::assertEquals('_id', $field->alias());
        self::assertEquals('id', $field->name());
        self::assertFalse($field->is('name'));
    }

    public function testShouldRunWhenModifyFromMap(): void
    {
        $field = new Field('name');
        $fieldMap = new FieldMap('_id', 'id');
        $field->mapping($fieldMap);

        self::assertEquals('name', $field->alias());
        self::assertEquals('id', $field->name());
    }

    public function testShouldRunWhenLookup(): void
    {
        $field = new Field('name');
        $field->lookup('tag.name');

        self::assertEquals('name', $field->alias());
        self::assertEquals('tag.name', $field->name());
    }
}
