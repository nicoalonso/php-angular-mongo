<?php declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\User\Reader\UserRead;
use App\Presentation\V1\User\UserView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

final class LoginController
{
    private const string CHALLENGE_VALUE = 'Bearer authorization_uri=https://login.microsoftonline.com/';

    public function __invoke(UserRead $reader): Response
    {
        try {
            $user = $reader->dispatch();
            $view = new UserView($user);

            // @codeCoverageIgnoreStart
        } catch (Throwable $e) {
            throw new UnauthorizedHttpException(self::CHALLENGE_VALUE, $e->getMessage());
        }
        // @codeCoverageIgnoreEnd

        return new JsonResponse($view);
    }
}
