<?php

namespace App\Tests\Infrastructure\Controller\V1\Borrow;

use App\Application\Borrow\Sanctioner\BorrowPenaltyEvent;
use App\Infrastructure\Controller\V1\Borrow\BorrowManualPenaltyController;
use App\Tests\Doubles\Infrastructure\Bus\DomainBusStub;
use App\Tests\Infrastructure\ControllerTestable;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Bus\assertDispatch;

class BorrowManualPenaltyControllerTest extends TestCase
{
    use ControllerTestable;

    private DomainBusStub $bus;
    private BorrowManualPenaltyController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = new DomainBusStub();
        $this->controller = new BorrowManualPenaltyController($this->bus);
    }

    public function testShouldRunWhenInvoke(): void
    {
        $response = $this->controller->__invoke();

        self::assertResponse($response, 202);
        assertDispatch($this->bus, BorrowPenaltyEvent::class);
    }
}
