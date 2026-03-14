<?php

namespace App\Tests\Application\Borrow\CheckIn;

use App\Application\Borrow\CheckIn\BorrowCheckIn;
use App\Application\Borrow\CheckIn\BorrowCheckInPayload;
use App\Domain\Borrow\Exception\BorrowNotFoundException;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowLineRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\BorrowRepositoryStub;
use App\Tests\Doubles\Infrastructure\Persistence\UserRepositoryStub;
use App\Tests\Fixtures\FixturePayload;
use App\Tests\Fixtures\Ref;
use PHPUnit\Framework\TestCase;
use function App\Tests\Doubles\Infrastructure\Persistence\assertStored;

class BorrowCheckInTest extends TestCase
{
    use FixturePayload;

    private BorrowRepositoryStub $repoBorrow;
    private BorrowLineRepositoryStub $repoBorrowLine;
    private BorrowCheckIn $checker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoBorrow = new BorrowRepositoryStub();
        $this->repoBorrowLine = new BorrowLineRepositoryStub($this->repoBorrow);
        $repoUser = new UserRepositoryStub();
        $this->checker = new BorrowCheckIn($this->repoBorrow, $this->repoBorrowLine, $repoUser);
    }

    public function testShouldFailWhenNotFound(): void
    {
        $data = $this->getPayload('borrow-checkin');
        $payload = new BorrowCheckInPayload($data);

        $this->expectException(BorrowNotFoundException::class);
        $this->checker->dispatch('invalid-id', $payload);
    }

    public function testShouldRunWhenChecked(): void
    {
        $this->repoBorrow->put(Ref::BorrowJohnDoe);
        $line1 = $this->repoBorrowLine->attach(Ref::BorrowLineJohnRomeoAndJuliet);
        $line2 = $this->repoBorrowLine->attach(Ref::BorrowLineJohnQuijote);

        $data = $this->getPayload('borrow-checkin');
        $data['lines'][0]['lineId'] = $line1->getId();
        $data['lines'][1]['lineId'] = $line2->getId();
        $payload = new BorrowCheckInPayload($data);

        $borrow = $this->checker->dispatch('123456', $payload);

        $this->assertEquals(1, $borrow->getTotalReturnedBooks());
        assertStored($this->repoBorrow);
        assertStored($this->repoBorrowLine);
    }
}
