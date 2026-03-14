<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 */
final class HomeController
{
    public function __invoke(): Response
    {
        return new Response('Library API');
    }
}
