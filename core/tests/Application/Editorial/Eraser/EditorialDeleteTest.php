<?php

namespace App\Tests\Application\Editorial\Eraser;

use App\Application\Editorial\Eraser\EditorialBookAssociatedException;
use App\Application\Editorial\Eraser\EditorialDelete;
use App\Domain\Editorial\Exception\EditorialNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;

class EditorialDeleteTest extends TestCase
{
    private EditorialRepositoryStub $repoEditorial;
    private BookRepositoryStub $repoBook;
    private EditorialDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub();
        $this->eraser = new EditorialDelete($this->repoEditorial, $this->repoBook);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(EditorialNotFoundException::class);
        $this->eraser->dispatch('non-existing-id');
    }

    public function testShouldFailWhenBooksRelated(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);
        $this->repoBook->attach(Ref::BookRomeoAndJuliet);

        $this->expectException(EditorialBookAssociatedException::class);
        $this->eraser->dispatch('1234546');
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $this->eraser->dispatch('1234546');

        assertRemoved($this->repoEditorial);
    }
}
