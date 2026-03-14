<?php declare(strict_types=1);

namespace App\Domain\Identity;

use Ramsey\Uuid\Uuid as RamseyUuid;

abstract class Payload
{
    protected Valet $data;
    protected string $name;

    public function __construct(array $payload)
    {
        $this->data = new Valet($payload);
        $this->name = $this->data->toString('name');
    }

    public function getName(): string
    {
        return trim($this->name);
    }

    public function toValidId(string $key = 'id'): ?string
    {
        $id = $this->data->toString($key);
        if (empty($id)) {
            return null;
        }

        if (!RamseyUuid::isValid($id)) {
            return null;
        }

        return $id;
    }

    public function toList(string $key): array
    {
        if (!$this->data->has($key)) {
            return [];
        }

        $item = $this->data->get($key);
        if (is_string($item)) {
            $item = preg_split('/[;,]/', $item);
        }
        if (!is_array($item)) {
            $item = (array) $item;
        }

        return array_values(array_unique(array_filter($item)));
    }
}
