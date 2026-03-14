<?php

namespace App\Tests\Presentation\Identity;

use App\Presentation\Identity\Result;
use Exception;
use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testShouldRunWhenCreate(): void
    {
        $result = new Result('data', 200, 'message');

        $this->assertSame('data', $result->getData());
        $this->assertSame(200, $result->getCode());
        $this->assertSame('message', $result->getMessage());

        $json = $result->jsonSerialize();
        $this->assertSame([
            'code' => 200,
            'message' => 'message',
        ], $json);
    }

    public function testShouldRunWhenSuccess(): void
    {
        $result = Result::success('data');

        $this->assertSame('data', $result->getData());
        $this->assertNull($result->getCode());
        $this->assertNull($result->getMessage());

        $json = $result->jsonSerialize();
        $this->assertSame([
            'data' => 'data',
        ], $json);
    }

    public function testShouldRunWhenError(): void
    {
        $result = Result::error(400, 'Bad Request');

        $this->assertNull($result->getData());
        $this->assertSame(400, $result->getCode());
        $this->assertSame('Bad Request', $result->getMessage());

        $json = $result->jsonSerialize();
        $this->assertSame([
            'code' => 400,
            'message' => 'Bad Request',
        ], $json);
    }

    public function testShouldRunWhenException(): void
    {
        $exception = new Exception('Something went wrong');
        $result = Result::exception(500, $exception);

        $this->assertNull($result->getData());
        $this->assertSame(500, $result->getCode());
        $this->assertSame('Something went wrong', $result->getMessage());

        $json = $result->jsonSerialize();
        $this->assertSame([
            'code' => 500,
            'message' => 'Something went wrong',
        ], $json);
    }
}
