<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\Creator\BookCreate;
use App\Infrastructure\Controller\V1\Book\BookCreateController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BookCreateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private BookRepositoryStub $repoBook;
    private AuthorRepositoryStub $repoAuthor;
    private EditorialRepositoryStub $repoEditorial;
    private BookCreate $creator;
    private BookCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub($this->repoAuthor, $this->repoEditorial);
        $repoUser = new UserRepositoryStub();

        $this->creator = new BookCreate(
            $this->repoBook,
            $this->repoAuthor,
            $this->repoEditorial,
            $repoUser,
        );
        $this->controller = new BookCreateController();
    }

    public function testShouldFailWhenAlreadyExists(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $data = $this->getPayload('book-create');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldFailWhenAuthorNotExists(): void
    {
        $data = $this->getPayload('book-create');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldFailWhenEditorialNotFound(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);

        $data = $this->getPayload('book-create');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldFailWhenInvalidPublishedAt(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $data = $this->override(detail: [])
            ->getPayload('book-create');
        $request = $this->createRequest(request: $data);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke($request, $this->creator);
    }

    public function testShouldRunWhenCreate(): void
    {
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $data = $this->getPayload('book-create');
        $request = $this->createRequest(request: $data);

        $response = $this->controller->__invoke($request, $this->creator);

        self::assertResponse($response, 201);
        assertStored($this->repoBook);
    }
}
