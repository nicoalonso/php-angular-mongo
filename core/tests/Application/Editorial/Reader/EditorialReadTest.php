<?php

namespace App\Tests\Application\Editorial\Reader;

use App\Application\Editorial\Reader\EditorialRead;
use App\Domain\Editorial\Exception\EditorialNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class EditorialReadTest extends TestCase
{
    private EditorialRepositoryStub $repoEditorial;
    private EditorialRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $this->reader = new EditorialRead($this->repoEditorial);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(EditorialNotFoundException::class);

        $this->reader->dispatch('unknown-editorial-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $editorial = $this->repoEditorial->put(Ref::EditorialAnaya);

        $result = $this->reader->dispatch($editorial->getId());

        self::assertEquals('Anaya', $result->getName());
    }
}
