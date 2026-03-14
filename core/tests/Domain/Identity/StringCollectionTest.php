<?php

namespace App\Tests\Domain\Identity;

use App\Domain\Identity\StringCollection;
use PHPUnit\Framework\TestCase;

class StringCollectionTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $col = new StringCollection();
        $col->add('test');

        self::assertCount(1, $col);
    }
}
