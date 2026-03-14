<?php

namespace App\Tests\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Eraser\EditorialDelete;
use App\Infrastructure\Controller\V1\Editorial\EditorialDeleteController;
use App\Tests\Doubles\Infrastructure\Persistence\BookRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertRemoved;

class EditorialDeleteControllerTest extends TestCase
{
    use ControllerTestable;

    private EditorialRepositoryStub $repoEditorial;
    private BookRepositoryStub $repoBook;
    private EditorialDelete $eraser;
    private EditorialDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $this->repoBook = new BookRepositoryStub();
        $this->eraser = new EditorialDelete($this->repoEditorial, $this->repoBook);
        $this->controller = new EditorialDeleteController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('non-existing-id', $this->eraser);
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);
        $this->repoBook->attach(Ref::BookRomeoAndJuliet);

        $this->expectException(BadRequestHttpException::class);
        $this->controller->__invoke('1234546', $this->eraser);
    }

    public function testShouldRunWhenRemoved(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $response = $this->controller->__invoke('1234546', $this->eraser);

        self::assertResponse($response, 204);
        assertRemoved($this->repoEditorial);
    }
}
