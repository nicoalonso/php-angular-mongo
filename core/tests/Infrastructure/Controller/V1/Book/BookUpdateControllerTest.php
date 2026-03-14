<?php

namespace App\Tests\Infrastructure\Controller\V1\Book;

use App\Application\Book\Updater\BookUpdate;
use App\Infrastructure\Controller\V1\Book\BookUpdateController;
use App\Tests\Doubles\Infrastructure\Persistence\AuthorRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BookUpdateControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private BookRepositoryStub $repoBook;
    private AuthorRepositoryStub $repoAuthor;
    private EditorialRepositoryStub $repoEditorial;
    private BookUpdate $updater;
    private array $payload;
    private BookUpdateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoAuthor = new AuthorRepositoryStub();
        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub($this->repoAuthor, $this->repoEditorial);
        $repoUser = new UserRepositoryStub();

        $this->updater = new BookUpdate(
            $this->repoBook,
            $this->repoAuthor,
            $this->repoEditorial,
            $repoUser,
        );
        $this->controller = new BookUpdateController();

        $this->payload = $this->getPayload('book-create');
    }

    public function testShouldFailWhenNotFound(): void
    {
        $request = $this->createRequest($this->payload);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldFailWhenBadRequestAuthorNotFound(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $request = $this->createRequest(request: $this->payload);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);

        $request = $this->createRequest();

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->updater);
    }

    public function testShouldRunWhenModify(): void
    {
        $this->repoBook->put(Ref::BookRomeoAndJuliet);
        $this->repoAuthor->put(Ref::AuthorShakespeare);
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $request = $this->createRequest(request: $this->payload);

        $response = $this->controller->__invoke('invalid-id', $request, $this->updater);

        self::assertResponse($response, 204);
        assertStored($this->repoBook);
    }
}
