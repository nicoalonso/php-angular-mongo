<?php

namespace App\Tests\Application\Author\Creator;

use App\Application\Author\Creator\AuthorCreate;
use App\Application\Author\Creator\AuthorCreatePayload;
use App\Domain\Author\Exception\AuthorAlreadyExistException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class AuthorCreateTest extends TestCase
{
    use FixturePayload;

    private AuthorRepositoryStub $repoAuthor;
    private AuthorCreate $creator;
    private AuthorCreatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->creator = new AuthorCreate($this->repoAuthor, $repoUser);

        $data = $this->getPayload('author');
        $this->payload = new AuthorCreatePayload($data);
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $this->expectException(AuthorAlreadyExistException::class);
        $this->creator->dispatch($this->payload);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->creator->dispatch($this->payload);

        assertStored($this->repoAuthor);
    }
}
