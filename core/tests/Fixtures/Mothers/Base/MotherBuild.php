<?php declare(strict_types=1);

namespace App\Tests\Fixtures\Mothers\Base;

use InvalidArgumentException;

final readonly class MotherBuild
{
    public function __construct(
        private string $fqn,
        private string $method,
    ) {}

    public function build(): mixed
    {
        if (!method_exists($this->fqn, $this->method) ||
            !is_callable([$this->fqn, $this->method])
        ) {
            throw new InvalidArgumentException("Method {$this->method} does not exist in class {$this->fqn}");
        }

        return call_user_func([$this->fqn, $this->method]);
    }
}
