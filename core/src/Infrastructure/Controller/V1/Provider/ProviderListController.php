<?php declare(strict_types=1);

namespace App\Infrastructure\Controller\V1\Provider;

use App\Application\Provider\List\ProviderList;
use App\Domain\Identity\Exception\BadRequestException;
use App\Domain\Identity\List\ListQuery;
use App\Presentation\Identity\ListView;
use App\Presentation\V1\Provider\ProviderReadView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ProviderListController
{
    public function __invoke(Request $request, ProviderLIst $lister): Response
    {
        try {
            $params = $request->query->all();
            $query = ListQuery::fromParams($params);
            $list = $lister->dispatch($query);
            $result = new ListView($list, ProviderReadView::class);

        } catch (BadRequestException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return new JsonResponse($result);
    }
}
