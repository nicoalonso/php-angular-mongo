<?php

namespace App\Tests\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\CheckIn\BorrowCheckIn;
use App\Infrastructure\Controller\V1\Borrow\BorrowCheckInController;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowCheckInControllerTest extends TestCase
{
    use ControllerTestable;
    use FixturePayload;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BorrowCheckIn $checker;
    private BorrowCheckInController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);
        $repoUser = new UserRepositoryStub();
        $this->checker = new BorrowCheckIn($this->repoBorrow, $this->repoBorrowLine, $repoUser);
        $this->controller = new BorrowCheckInController();
    }

    public function testShouldFailWhenNotFound(): void
    {
        $data = $this->getPayload('borrow-checkin');
        $request = $this->createRequest(request: $data);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('invalid-id', $request, $this->checker);
    }

    public function testShouldRunWhenCheckIn(): void
    {
        $this->repoBorrow->put(Ref::BorrowJohnDoe);
        $line1 = $this->repoBorrowLine->attach(Ref::BorrowLineJohnRomeoAndJuliet);
        $line2 = $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);

        $data = $this->getPayload('borrow-checkin');
        $data['lines'][0]['lineId'] = $line1->getId();
        $data['lines'][1]['lineId'] = $line2->getId();
        $request = $this->createRequest(request: $data);

        $response = $this->controller->__invoke('invalid-id', $request, $this->checker);

        self::assertResponse($response,204);
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
