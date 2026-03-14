<?php

namespace App\Tests\Infrastructure\Controller\V1\Sequence;

use App\Application\Sequence\Simulator\SequenceSimulate;
use App\Infrastructure\Controller\V1\Sequence\SequenceSimulateController;
use App\Tests\Doubles\Infrastructure\Persistence\SequenceNumberRepositoryStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SequenceSimulateControllerTest extends TestCase
{
    use ControllerTestable;

    private SequenceSimulate $simulate;
    private SequenceSimulateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $repoSequenceNumber = new SequenceNumberRepositoryStub();
        $this->simulate = new SequenceSimulate($repoSequenceNumber);
        $this->controller = new SequenceSimulateController();
    }

    public function testShouldFailWhenBadRequest(): void
    {
        $this->expectException(BadRequestHttpException::class);

        $this->controller->__invoke('wrong-type', $this->simulate);
    }

    public function testShouldRunWhenSimulate(): void
    {
        $response = $this->controller->__invoke('borrow', $this->simulate);

        $data = self::assertResponse($response);
        self::assertEquals('P-00001', $data['number']);
    }
}
