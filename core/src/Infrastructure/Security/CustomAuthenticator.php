<?php declare(strict_types=1);

namespace App\Infrastructure\Security;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @codeCoverageIgnore
 */
final class CustomAuthenticator extends AbstractAuthenticator
{
    private const string USERNAME_FIELD = 'username';
    private const string DISPLAY_NAME_FIELD = 'displayName';
    private const string ROLES_FIELD = 'roles';

    private LoggerInterface $log;

    /**
     * @param Logger $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->log = $logger->withName('security');
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $session = $request->getSession();

        // Simulate user authentication
        $username = $session->get(self::USERNAME_FIELD);
        if (!empty($username)) {
            return new SelfValidatingPassport(new UserBadge($username));
        }

        $username = 'john.doe@gmail.com';
        $session->set(self::USERNAME_FIELD, $username);
        $session->set(self::DISPLAY_NAME_FIELD, 'John Doe');
        $session->set(self::ROLES_FIELD, ['admin']);

        // Patch, if not access to all variables it is not saved on the session
        $items = count($session->all());
        $this->log->debug('Session updated', ['items' => $items]);
        $session->save();

        $badge = new UserBadge($username);
        return new SelfValidatingPassport($badge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
