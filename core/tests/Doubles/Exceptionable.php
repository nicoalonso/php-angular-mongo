<?php

namespace App\Tests\Doubles;

use Exception;

trait Exceptionable
{
    private ?Exception $exception = null;

    public function error(Exception|string $exception): void
    {
        if ($exception instanceof Exception) {
            $this->exception = $exception;
        } else {
            $this->exception = new Exception($exception);
        }
    }

    protected function throw(): void
    {
        if ($this->exception !== null) {
            throw $this->exception;
        }
    }
}