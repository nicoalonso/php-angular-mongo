<?php declare(strict_types=1);

namespace App\Domain\Identity;

use DateTime;
use DateTimeImmutable;
use Throwable;

define('DATE_SHORT', 'Y-m-d');
define('DATE_ISO8601U', 'Y-m-d\TH:i:s.uO');

final class Valet
{
    private array $keysMap;

    public function __construct(private array $data = [])
    {
        $keys = array_keys($data);
        $insensitiveKeys = array_map('strtolower', $keys);
        $this->keysMap = array_combine($insensitiveKeys, $keys);
    }

    /**
     * @param string|string[] $keyName
     */
    public function toString(string|array $keyName, ?string $default = ''): ?string
    {
        $value = $this->get($keyName, $default);
        if (null === $value && null === $default) {
            return null;
        }
        return (string)$value;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toInt(string|array $keyName, ?int $default = 0): ?int
    {
        $value = $this->get($keyName, $default);
        if (null === $value && null === $default) {
            return null;
        }
        return (int)$value;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toFloat(string|array $keyName, ?float $default = 0): ?float
    {
        $value = $this->get($keyName, $default);
        if (null === $value && null === $default) {
            return null;
        }
        return (float)$value;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toBool(string|array $keyName, ?bool $default = false): ?bool
    {
        $value = $this->get($keyName, $default);
        if (null === $value && null === $default) {
            return null;
        }

        if (is_string($value)) {
            return strcasecmp($value, 'true') === 0 ||
                strcasecmp($value, '1') === 0;
        }

        return (bool)$value;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toDateImmutable(string|array $keyName, string $format = DATE_ATOM, bool $nullable = true, ?string $modifier = null): ?DateTimeImmutable
    {
        $date = $this->toDate($keyName, $format, $nullable, $modifier);

        if (null === $date) {
            return null;
        }

        return DateTimeImmutable::createFromMutable($date);
    }

    /**
     * @param string|string[] $keyName
     */
    public function toDate(string|array $keyName, string $format = DATE_ATOM, bool $nullable = true, ?string $modifier = null): ?DateTime
    {
        $date = $nullable ? null : new DateTime();

        $value = $this->get($keyName);
        $canModify = false;
        if (is_int($value)) {
            $date = new DateTime("@$value");
        } else if (is_string($value)) {
            if (null !== $modifier) {
                $canModify = !str_contains($value, 'T');
                $format = $canModify ? DATE_SHORT : DATE_ATOM;
            }

            $dateByFormat = DateTime::createFromFormat($format, $value);
            if (false !== $dateByFormat) {
                $date = $dateByFormat;
            }

            if ($canModify) {
                try {
                    $auxDate = $date;
                    $date->modify($modifier);
                    // @codeCoverageIgnoreStart
                } catch (Throwable) {
                    $date = $auxDate;
                }
                // @codeCoverageIgnoreEnd
            }
        }

        return $date;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toValet(string|array $keyName): self
    {
        return new self($this->toArray($keyName));
    }

    /**
     * @param string|string[] $keyName
     * @return Valet[]
     */
    public function toList(string|array $keyName): array
    {
        $list = [];
        $value = $this->toArray($keyName);
        foreach ($value as $item) {
            $list[] = new self((array)$item);
        }
        return $list;
    }

    /**
     * @param string|string[] $keyName
     * @return array<string, mixed>
     */
    public function toAssocArray(string|array $keyName): array
    {
        $list = [];
        $values = $this->toArray($keyName);
        foreach ($values as $key => $value) {
            if (is_string($key)) {
                $list[$key] = $value;
            }
        }

        return $list;
    }

    /**
     * @param string|string[] $keyName
     * @return string[]
     */
    public function toStringArray(string|array $keyName): array
    {
        $list = [];
        $value = $this->toArray($keyName);
        foreach ($value as $item) {
            $list[] = (string)$item;
        }
        return $list;
    }

    /**
     * @param string|string[] $keyName
     */
    public function toArray(string|array $keyName, array $default = []): array
    {
        return (array)$this->get($keyName, $default);
    }

    /**
     * if `$keyName` is an array, it will return the value of the first key that exists in the data.
     * If the key does not exist, it will return the default value.
     *
     * @param string|string[] $keyName
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string|array $keyName, mixed $default = null): mixed
    {
        $currentKey = null;
        if (is_array($keyName)) {
            $keys = $keyName;
        } else {
            $keys = [$keyName];
        }

        foreach ($keys as $key) {
            $validKey = $this->getValidKey($key);
            if (array_key_exists($validKey, $this->data)) {
                $currentKey = $validKey;
                break;
            }
        }

        if (null === $currentKey) {
            return $default;
        }

        return $this->data[$currentKey];
    }

    public function isString(string $keyName): bool
    {
        $validKey = $this->getValidKey($keyName);
        if (!array_key_exists($validKey, $this->data)) {
            return false;
        }
        return is_string($this->data[$validKey]);
    }

    public function isArray(string $keyName): bool
    {
        $validKey = $this->getValidKey($keyName);
        if (!array_key_exists($validKey, $this->data)) {
            return false;
        }
        return is_array($this->data[$validKey]);
    }

    public function has(string $keyName): bool
    {
        $validKey = $this->getValidKey($keyName);
        return array_key_exists($validKey, $this->data);
    }

    public function add(string $keyName, mixed $value): void
    {
        $key = strtolower($keyName);
        $this->keysMap[$key] = $keyName;
        $this->data[$keyName] = $value;
    }

    private function getValidKey(string $keyName): string
    {
        $keyLower = strtolower($keyName);
        if (array_key_exists($keyLower, $this->keysMap)) {
            return $this->keysMap[$keyLower];
        }

        return $keyName;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
