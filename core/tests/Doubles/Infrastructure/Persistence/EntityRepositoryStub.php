<?php declare(strict_types=1);

namespace App\Tests\Doubles\Infrastructure\Persistence;

use App\Domain\Identity\IdentityRepository;
use App\Domain\Identity\List\ListQuery;
use App\Domain\Identity\List\ListResult;
use App\Domain\Identity\List\Pagination;
use App\Tests\Fixtures\Ref;
use App\Tests\Doubles\Exceptionable;
use App\Tests\Doubles\Spyable;
use Doctrine\Common\Collections\ArrayCollection;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

/**
 * @psalm-template T
 * @template-implements IdentityRepository<T>
 */
abstract class EntityRepositoryStub implements IdentityRepository
{
    use Spyable;
    use Exceptionable;

    /** @var ArrayCollection<T> */
    public ArrayCollection $repositoryData;
    /** @var ArrayCollection<T> */
    public ArrayCollection $list;
    /** @var T */
    public mixed $read = null;
    /** @var T */
    public mixed $stored = null;
    /** @var T */
    public mixed $removed = null;

    public function __construct()
    {
        $this->repositoryData = new ArrayCollection();
        $this->list = new ArrayCollection();
        $this->makeFixtures();
    }

    /** @return T | null */
    public function obtainById(string $id)
    {
        return $this->read;
    }

    /** @param T $entity */
    public function save($entity): void
    {
        $this->throw();
        $this->stored = $entity;
    }

    /** @param T $entity */
    public function remove($entity): void
    {
        $this->throw();
        $this->removed = $entity;
    }

    public function obtainByQuery(ListQuery $query): ListResult
    {
        $pagination = new Pagination($this->list->count(), $query->page(), $query->limit());
        return new ListResult($this->list, $pagination);
    }

    public function attachAll(): ArrayCollection
    {
        $this->list = $this->repositoryData;
        return $this->list;
    }

    /**
     * @return ?T
     */
    public function attach(Ref $ref): mixed
    {
        $item = $this->get($ref);
        $this->list[] = $item;
        return $item;
    }

    /**
     * @param T $item
     */
    public function manualAttach(mixed $item): void
    {
        $this->list[] = $item;
    }

    /**
     * @return ?T
     */
    public function put(Ref $ref): mixed
    {
        $item = $this->get($ref);
        $this->read = $item;
        return $item;
    }

    /**
     * @param T $item
     */
    public function manualPut(mixed $item): void
    {
        $this->read = $item;
    }

    public function multiplier(int $times): void
    {
        for ($i = 0; $i < $times; $i++) {
            foreach ($this->repositoryData as $item) {
                $this->list[] = $item;
            }
        }
    }

    public function clear(): void
    {
        $this->list->clear();
    }

    /**
     * @param Ref $key
     * @return ?T
     */
    public function get(Ref $key): mixed
    {
        if (!$this->repositoryData->containsKey($key->value)) {
            return null;
        }

        return $this->repositoryData[$key->value];
    }

    abstract protected function makeFixtures(): void;

    /**
     * @param Ref $key
     * @param object $fixture
     * @return void
     */
    protected function addFixture(Ref $key, object $fixture): void
    {
        $this->repositoryData->set($key->value, $fixture);
    }
}

function assertStored(EntityRepositoryStub $repo): void
{
    assertNotNull($repo->stored, 'Expected to store an entity, but stored is null');
}

function assertNotStored(EntityRepositoryStub $repo): void
{
    assertNull($repo->stored, 'Expected not to store an entity, but stored is not null');
}

function assertRemoved(EntityRepositoryStub $repo): void
{
    assertNotNull($repo->removed, 'Expected to remove an entity, but removed is null');
}

function assertNotRemoved(EntityRepositoryStub $repo): void
{
    assertNull($repo->removed, 'Expected not to remove an entity, but removed is not null');
}
