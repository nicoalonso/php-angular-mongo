<?php declare(strict_types=1);

namespace App\Tests\Infrastructure;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

trait ControllerTestable
{
    protected bool $hasSession = false;
    protected Session $session;
    private string $username = 'jdoe@gmail.com';
    /** @var string[] */
    private array $groups = ['global'];

    protected function enableSession(): void
    {
        $this->hasSession = true;
    }

    protected function changeUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string[] $groups
     */
    protected function changeGroups(array $groups): void
    {
        $this->groups = $groups;
    }

    protected function createRequest(array $query = [], array $request = [], array $files = []): Request
    {
        $validFiles = $this->getValidFiles($files);
        $request = new Request($query, $request, [], [], $validFiles);
        return $this->makeSession($request);
    }

    protected function getValidFiles(array $files): array
    {
        $validFiles = [];
        foreach ($files as $fileKey => $fileValue) {
            if (is_string($fileValue)) {
                $originalName = basename($fileValue);
                $validFiles[$fileKey] = new UploadedFile($fileValue, $originalName);
            } else {
                $validFiles[$fileKey] = $fileValue;
            }
        }
        return $validFiles;
    }

    protected function makeSession(Request $request): Request
    {
        if (!$this->hasSession) {
            return $request;
        }

        $this->session = new Session(new MockFileSessionStorage());
        $this->session->set('username', $this->username);
        $this->session->set('displayName', 'John Doe');
        $this->session->set('groups', $this->groups);

        $request->setSession($this->session);
        return $request;
    }

    public static function assertResponse(Response $response, int $statusCode = 200): array
    {
        self::assertEquals($statusCode, $response->getStatusCode());
        if (
            $statusCode === Response::HTTP_NO_CONTENT ||
            ($statusCode === Response::HTTP_ACCEPTED && empty($response->getContent()))
        ) {
            return [];
        }

        self::assertNotEmpty($response->getContent());
        $json = json_decode($response->getContent(), true);

        if (array_key_exists('data', $json)) {
            return $json['data'];
        }

        if (array_key_exists('items', $json)) {
            return $json['items'];
        }

        return $json;
    }
}
