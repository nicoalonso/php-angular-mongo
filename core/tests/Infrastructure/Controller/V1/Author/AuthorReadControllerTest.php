<?php

namespace App\Tests\Infrastructure\Controller\V1\Author;

use App\Application\Author\Reader\AuthorRead;
use App\Infrastructure\Controller\V1\Author\AuthorReadController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorReadControllerTest extends TestCase
{
    use ControllerTestable;

    private AuthorRepositoryStub $repoAuthor;
    private AuthorRead $reader;
    private AuthorReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->reader = new AuthorRead($this->repoAuthor);
        $this->controller = new AuthorReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('William Shakespeare', $data['name']);
    }
}
