<?php

namespace App\Tests\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\Reader\EditorialRead;
use App\Infrastructure\Controller\V1\Editorial\EditorialReadController;
use App\Tests\Doubles\Infrastructure\Persistence\EditorialRepositoryStub;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditorialReadControllerTest extends TestCase
{
    use ControllerTestable;

    private EditorialRepositoryStub $repoEditorial;
    private EditorialRead $reader;
    private EditorialReadController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoEditorial = new EditorialRepositoryStub();
        $this->reader = new EditorialRead($this->repoEditorial);
        $this->controller = new EditorialReadController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->controller->__invoke('unknown-book-id', $this->reader);
    }

    public function testShouldRunWhenRead(): void
    {
        $this->repoEditorial->put(Ref::EditorialAnaya);

        $response = $this->controller->__invoke('1234567890', $this->reader);

        $data = self::assertResponse($response);
        self::assertEquals('Anaya', $data['name']);
    }
}
