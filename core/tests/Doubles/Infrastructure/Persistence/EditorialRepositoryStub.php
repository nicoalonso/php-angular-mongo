<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Editorial\Editorial;
use App\Domain\Editorial\EditorialRepository;
use App\Tests\Fixtures\Mothers\EditorialMother;
use App\Tests\Fixtures\Ref;

final class EditorialRepositoryStub extends EntityRepositoryStub implements EditorialRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtainByName(string $name): ?Editorial
    {
        return $this->read;
    }

    protected function makeFixtures(): void
    {
        $anaya = EditorialMother::anaya();
        $this->addFixture(Ref::EditorialAnaya, $anaya);
    }
}
