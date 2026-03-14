<?php declare(strict_types=1);

namespace App\Domain\Identity;

use App\Domain\Identity\Exception\IdEmptyException;
use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Identity
{
    protected string $id;

    public function __construct(?string $id = null)
    {
        if (null !== $id) {
            $this->isValidId($id);
            $this->id = $id;
        } else {
            $this->id = self::makeUuid();
        }
    }

    public static function checkUuid(string $uuid): bool
    {
        return RamseyUuid::isValid($uuid);
    }

    public static function makeUuid(): string
    {
        $idV4 = RamseyUuid::uuid4()->toString();
        return RamseyUuid::uuid5($idV4, static::class)->toString();
    }

    private function isValidId(string $id): void
    {
        if (empty($id)) {
            throw new IdEmptyException();
        }
    }

    public function isSame($entity): bool
    {
        return
            $entity instanceof static &&
            $this->id === $entity->getId();
    }

    public function getId(): string
    {
        return $this->id;
    }
}
