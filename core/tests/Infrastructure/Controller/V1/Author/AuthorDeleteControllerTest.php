<?php

namespace App\Tests\Infrastructure\Controller\V1\Author;

use App\Application\Author\Eraser\AuthorDelete;
use App\Infrastructure\Controller\V1\Author\AuthorDeleteController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private AuthorRepositoryStub $repoAuthor;
    private BookRepositoryStub $repoBook;
    private AuthorDelete $eraser;
    private AuthorDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoBook = new BookRepositoryStub();
        $this->eraser = new AuthorDelete($this->repoAuthor, $this->repoBook);
        $this->controller = new AuthorDeleteController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('non-existing-id', $this->eraser);
    }

    public function testShouldRunWhenBooksRelated(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoBook->attachAll();

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('author-id-1', $this->eraser);
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $response = $this->controller->__invoke('author-id-1', $this->eraser);

        self::assertResponse($response, 204);
        self::assertNotNull($this->repoAuthor->removed);
    }
}
