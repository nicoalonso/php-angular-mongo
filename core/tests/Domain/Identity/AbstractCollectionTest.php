<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\AbstractCollection;
use App\Domain\Identity\Exception\CollectionMismatchException;
use App\Domain\Identity\Identity;
use PHPUnit\Framework\TestCase;

class ColDummyIdentity extends Identity {}

class DummyCollection extends AbstractCollection
{
    public function getType(): string
    {
        return ColDummyIdentity::class;
    }
}

class AbstractCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $col = new DummyCollection();

        self::assertEmpty($col);
        self::assertEmpty($col->toArray());
    }

    public function testShouldFailWhenInvalidType(): void
    {
        $this->expectException(CollectionMismatchException::class);
        new DummyCollection([1]);
    }

    public function testShouldCreateWhenFromOtherCollection(): void
    {
        $dummy = new ColDummyIdentity();
        $col1 = new DummyCollection([$dummy]);
        $col2 = new DummyCollection($col1);

        self::assertCount(1, $col2);
    }

    public function testShouldRunWhenExtendsDictCollection(): void
    {
        $dummy = new ColDummyIdentity();
        $col1 = new DummyCollection(['test' => $dummy]);
        $col2 = new DummyCollection();
        $col2->extend($col1);

        self::assertCount(1, $col2);
        self::assertEquals($dummy, $col2->first());
        self::assertEquals($dummy, $col2->get('test'));
    }

    public function testShouldRunWhenExtendsCollection(): void
    {
        $dummy = new ColDummyIdentity();
        $col1 = new DummyCollection([$dummy]);
        $col2 = new DummyCollection();
        $col2->extend($col1);

        self::assertCount(1, $col2);
        self::assertEquals($dummy, $col2->first());
    }

    public function testShouldFailWhenAddWrongElement(): void
    {
        $col1 = new DummyCollection();

        $this->expectException(CollectionMismatchException::class);
        $col1->add('string');
    }

    public function testShouldFailWhenSetWrongElement(): void
    {
        $col1 = new DummyCollection();

        $this->expectException(CollectionMismatchException::class);
        $col1->set('key', 'value');
    }

    public function testShouldRunWhenMap(): void
    {
        $dummy = new ColDummyIdentity();
        $col1 = new DummyCollection([$dummy]);

        $result = $col1->map(fn(ColDummyIdentity $entity) => $entity->getId());

        self::assertCount(1, $result);
        self::assertEquals($dummy->getId(), $result->first());
    }

    public function testShouldRunWhenSortAsc(): void
    {
        $dummy1 = new ColDummyIdentity('def');
        $dummy2 = new ColDummyIdentity('zxy');
        $dummy3 = new ColDummyIdentity('abc');

        $col1 = new DummyCollection([$dummy1, $dummy2, $dummy3]);
        $colSorted = $col1->sort('id');

        self::assertEquals('abc', $colSorted->first()->getId());
        self::assertEquals('zxy', $colSorted->last()->getId());
    }

    public function testShouldRunWhenSortDesc(): void
    {
        $dummy1 = new ColDummyIdentity('def');
        $dummy2 = new ColDummyIdentity('zxy');
        $dummy3 = new ColDummyIdentity('abc');

        $col1 = new DummyCollection([$dummy1, $dummy2, $dummy3]);
        $colSorted = $col1->sort('id', 'desc');

        self::assertEquals('abc', $colSorted->last()->getId());
        self::assertEquals('zxy', $colSorted->first()->getId());
    }

    public function testShouldRunWhenUserSort(): void
    {
        $dummy1 = new ColDummyIdentity('def');
        $dummy2 = new ColDummyIdentity('zxy');
        $dummy3 = new ColDummyIdentity('abc');

        $col1 = new DummyCollection([$dummy1, $dummy2, $dummy3]);
        $colSorted = $col1->usort(fn(ColDummyIdentity $a, ColDummyIdentity $b) => $a->getId() <=> $b->getId());

        self::assertEquals('abc', $colSorted->first()->getId());
        self::assertEquals('zxy', $colSorted->last()->getId());
    }
}
