<?php

namespace App\Tests\Application\Author\Eraser;

use App\Application\Author\Eraser\AuthorBookAssociatedException;
use App\Application\Author\Eraser\AuthorDelete;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class AuthorDeleteTest extends TestCase
{
    private AuthorRepositoryStub $repoAuthor;
    private BookRepositoryStub $repoBook;
    private AuthorDelete $eraser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoBook = new BookRepositoryStub();
        $this->eraser = new AuthorDelete($this->repoAuthor, $this->repoBook);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(AuthorNotFoundException::class);

        $this->eraser->dispatch('non-existing-id');
    }

    public function testShouldFailWhenRelatedBooks(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoBook->attachAll();

        $this->expectException(AuthorBookAssociatedException::class);
        $this->eraser->dispatch('author-id-1');
    }

    public function testShouldRunWhenDelete(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $this->eraser->dispatch('author-id-1');

        self::assertNotNull($this->repoAuthor->removed);
    }
}
