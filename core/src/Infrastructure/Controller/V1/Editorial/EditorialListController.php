<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Editorial;

use App\Application\Editorial\List\EditorialList;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\List\ListQuery;
use App\Presentation\Identity\ListView;
use App\Presentation\V1\Editorial\EditorialReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class EditorialListController
{
    public function __invoke(Request $request, EditorialList $lister): Response
    {
        try {
            $params = $request->query->all();
            $query = ListQuery::fromParams($params);
            $list = $lister->dispatch($query);
            $result = new ListView($list, EditorialReadView::class);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
