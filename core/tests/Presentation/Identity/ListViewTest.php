<?php

namespace App\Tests\Presentation\Identity;

use App\Domain\Identity\Identity;
use App\Domain\Identity\List\ListResult;
use App\Domain\Identity\List\Pagination;
use App\Presentation\Identity\Exception\EntityViewClassNotExistException;
use App\Presentation\Identity\Exception\EntityViewNotSerializableException;
use App\Presentation\Identity\ListView;
use App\Presentation\Identity\Result;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class DummyEntityNamed extends Identity
{
    public function __construct(
        private readonly string $type,
    ) {
        parent::__construct();
    }

    public function getName(): string
    {
        return $this->type;
    }
}

class DummyEntityView extends Result
{
    public function __construct(DummyEntityNamed $entity)
    {
        parent::__construct($entity);
    }

    /**
     * @param DummyEntityNamed $data
     */
    public static function serialize(mixed $data): array
    {
        return [
            'id' => $data->getId(),
            'name' => $data->getName(),
        ];
    }
}

readonly class DummyWithSerialize implements JsonSerializable
{
    public function __construct(private DummyEntityNamed $entity) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->entity->getId(),
            'name' => $this->entity->getName(),
        ];
    }
}

class ListViewTest extends TestCase
{
    public function testShouldFailWhenClassNotExist(): void
    {
        $result = new ListResult();

        $this->expectException(EntityViewClassNotExistException::class);
        new ListView($result, 'dummy');
    }

    public function testShouldFailWhenNotSerialize(): void
    {
        $result = new ListResult();

        $this->expectException(EntityViewNotSerializableException::class);
        new ListView($result, EntityViewNotSerializableException::class);
    }

    public function testShouldRunWhenCreate(): void
    {
        $items = new ArrayCollection([
            new DummyEntityNamed('dummy'),
            new DummyEntityNamed('dummy2'),
        ]);

        $pagination = new Pagination($items->count(), 1, 25);
        $result = new ListResult($items, $pagination);

        $view = new ListView($result, DummyEntityView::class);
        $data = $view->jsonSerialize();

        self::assertArrayHasKey('items', $data);
        self::assertArrayHasKey('pagination', $data);
        self::assertGreaterThanOrEqual(1, count($data['items']));
    }

    public function testShouldRunWhenUsingClassWithInterface(): void
    {
        $items = new ArrayCollection([
            new DummyEntityNamed('dummy'),
            new DummyEntityNamed('dummy2'),
        ]);
        $pagination = new Pagination($items->count(), 1, 25);
        $result = new ListResult($items, $pagination);

        $view = new ListView($result, DummyWithSerialize::class);
        $data = $view->jsonSerialize();

        self::assertArrayHasKey('items', $data);
        self::assertArrayHasKey('pagination', $data);
        self::assertGreaterThanOrEqual(1, count($data['items']));
    }
}
