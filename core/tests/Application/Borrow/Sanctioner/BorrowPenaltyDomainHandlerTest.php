<?php

namespace App\Tests\Application\Borrow\Sanctioner;

use App\Application\Borrow\Sanctioner\BorrowPenalty;
use App\Application\Borrow\Sanctioner\BorrowPenaltyDomainHandler;
use App\Application\Borrow\Sanctioner\BorrowPenaltyEvent;
use App\Tests\Doubles\Hydratable;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Fixtures\Ref;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowPenaltyDomainHandlerTest extends TestCase
{
    use Hydratable;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private MockObject $logger;
    private BorrowPenaltyDomainHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);

        $this->logger = $this->createMock(LoggerInterface::class);

        $sanctioner = new BorrowPenalty(
            $this->repoBorrow,
            $this->repoBorrowLine,
            $this->logger,
        );
        $this->handler = new BorrowPenaltyDomainHandler(
            $sanctioner,
            $this->logger,
        );
    }

    public function testShouldRunWhenFoundPenalties(): void
    {
        $borrow = $this->repoBorrow->attach(Ref::BorrowJohnDoe);
        $dueDate = new DateTimeImmutable('-16 days');
        $this->hydrateProperty($borrow, 'dueDate', $dueDate);
        $line = $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);

        $this->logger->expects($this->never())->method('error');

        $event = new BorrowPenaltyEvent();
        $this->handler->__invoke($event);

        self::assertTrue($borrow->hasPenalty());
        self::assertEquals(10.0, $borrow->getPenaltyAmount());
        self::assertTrue($line->hasPenalty());
        self::assertEquals(10.0, $line->getPenaltyAmount());
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
