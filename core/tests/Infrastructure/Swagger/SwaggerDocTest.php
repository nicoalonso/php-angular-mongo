<?php

namespace App\Tests\Infrastructure\Swagger;

use App\Infrastructure\Swagger\SwaggerDoc;
use OpenApi\Annotations\OpenApi;
use PHPUnit\Framework\TestCase;

class SwaggerDocTest extends TestCase
{
    public function testShouldRunWhenDescribeAreaV1(): void
    {
        $doc = new SwaggerDoc('v1');
        $swagger = new OpenApi([]);
        $doc->describe($swagger);

        self::assertGreaterThanOrEqual(1, count($swagger->paths));
    }
}
