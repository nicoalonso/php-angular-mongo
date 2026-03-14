<?php

namespace App\Tests\Application\Author\Updater;

use App\Application\Author\Updater\AuthorUpdate;
use App\Application\Author\Updater\AuthorUpdatePayload;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class AuthorUpdateTest extends TestCase
{
    use FixturePayload;

    private AuthorRepositoryStub $repoAuthor;
    private AuthorUpdate $updater;
    private AuthorUpdatePayload $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $repoUser = new UserRepositoryStub();
        $this->updater = new AuthorUpdate($this->repoAuthor, $repoUser);

        $data = $this->getPayload('author');
        $this->payload = new AuthorUpdatePayload($data);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(AuthorNotFoundException::class);

        $this->updater->dispatch('non-existing-id', $this->payload);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $this->updater->dispatch('12345678', $this->payload);

        assertStored($this->repoAuthor);
    }
}
