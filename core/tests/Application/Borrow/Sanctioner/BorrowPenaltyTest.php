<?php

namespace App\Tests\Application\Borrow\Sanctioner;

use App\Application\Borrow\Sanctioner\BorrowPenalty;
use App\Tests\Doubles\Hydratable;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Fixtures\Ref;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use function App\Tests\Doubles\Infrastructure\Persistence\assertNotStored;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowPenaltyTest extends TestCase
{
    use Hydratable;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BorrowPenalty $sanctioner;
    private MockObject $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);

        $this->logger = $this->createMock(LoggerInterface::class);

        $this->sanctioner = new BorrowPenalty(
            $this->repoBorrow,
            $this->repoBorrowLine,
            $this->logger,
        );
    }

    public function testShouldFailWhenBorrowLineNotFound(): void
    {
        $borrow = $this->repoBorrow->attach(Ref::BorrowJohnDoe);
        $dueDate = new DateTimeImmutable('-16 days');
        $this->hydrateProperty($borrow, 'dueDate', $dueDate);

        $this->logger->expects($this->once())->method('error');

        $count = $this->sanctioner->dispatch();

        self::assertEquals(0, $count);
        assertNotStored($this->repoBorrow);
        assertNotStored($this->repoBorrowLine);
    }

    public function testShouldRunWhenFoundPenalties(): void
    {
        $borrow = $this->repoBorrow->attach(Ref::BorrowJohnDoe);
        $dueDate = new DateTimeImmutable('-16 days');
        $this->hydrateProperty($borrow, 'dueDate', $dueDate);

        $line = $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);

        $this->logger->expects($this->never())->method('error');

        $count = $this->sanctioner->dispatch();

        self::assertEquals(1, $count);
        self::assertTrue($borrow->hasPenalty());
        self::assertEquals(10.0, $borrow->getPenaltyAmount());
        self::assertTrue($line->hasPenalty());
        self::assertEquals(10.0, $line->getPenaltyAmount());
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
