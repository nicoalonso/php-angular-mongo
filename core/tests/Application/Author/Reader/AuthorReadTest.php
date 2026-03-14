<?php

namespace App\Tests\Application\Author\Reader;

use App\Application\Author\Reader\AuthorRead;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;

class AuthorReadTest extends TestCase
{
    private AuthorRepositoryStub $repoAuthor;
    private AuthorRead $reader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->reader = new AuthorRead($this->repoAuthor);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(AuthorNotFoundException::class);

        $this->reader->dispatch('unknown-author-id');
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoAuthor->put(Ref::AuthorCervantes);

        $author = $this->reader->dispatch('1234567890');

        self::assertEquals('Miguel de Cervantes', $author->getName());
    }
}
