<?php declare(strict_types=1);

namespace App\Presentation\Identity;

use JsonSerializable;
use Throwable;

class Result implements JsonSerializable
{
    public function __construct(
        protected mixed $data = null,
        protected ?int $code = null,
        protected ?string $message = null,
    ) {}

    public static function success($data = []): self
    {
        return new self($data);
    }

    public static function error(int $code, string $message): self
    {
        return new self(code: $code, message: $message);
    }

    public static function exception(int $code, Throwable $exception): self
    {
        return new self(code: $code, message: $exception->getMessage());
    }

    public function jsonSerialize(): array
    {
        if (null !== $this->code) {
            return [
                'code' => $this->code,
                'message' => $this->message,
            ];
        }

        return $this->json();
    }

    protected function json(): array
    {
        return [
            'data' => static::serialize($this->data),
        ];
    }

    public static function serialize(mixed $data): mixed
    {
        return $data;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }
}
