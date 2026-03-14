<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Author;

use App\Application\Author\List\AuthorList;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\List\ListQuery;
use App\Presentation\Identity\ListView;
use App\Presentation\V1\Author\AuthorReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class AuthorListController
{
    public function __invoke(Request $request, AuthorList $lister): Response
    {
        try {
            $params = $request->query->all();
            $query = ListQuery::fromParams($params);
            $list = $lister->dispatch($query);
            $result = new ListView($list, AuthorReadView::class);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
