<?php

namespace App\Tests\Fixtures;

trait FixturePayload
{
    use FixtureLoadable;

    protected ?array $_overrides = null;

    protected function override(...$overrides): self
    {
        if (count($overrides) > 0) {
            $this->_overrides = $overrides;
        }

        return $this;
    }

    protected function getPayload(string $name): array
    {
        $this->fixture('Payload', 'json');
        $values = json_decode($this->load($name), true);

        if (empty($this->_overrides)) {
            return $values;
        }

        $values = $this->merge($values, $this->_overrides);
        $this->_overrides = null;
        return $values;
    }

    protected function merge(array $values, array $overrides): array
    {
        $fields = [];
        foreach ($values as $key => $value) {
            $fields[$key] = $overrides[$key] ?? $value;
        }
        return $fields;
    }
}
